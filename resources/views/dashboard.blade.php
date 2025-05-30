<!doctype html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <script>
        // Redirect if no token found
        if (!localStorage.getItem('token')) {
            window.location.href = '/';
        }
    </script>

    <div class="container">
        <h1>Bank Management Dashboard</h1>

        <form onsubmit="event.preventDefault(); getBankDetails()">
            <label for="bank_id">Bank: </label>
            <select id="bank_id" required onchange="populateBranchDropdown(this.value)">
                <option value="">Select a bank</option>
            </select>
            <label for="branch_id">Branch (Optional):</label>
            <select id="branch_id">
                <option value="">Select a branch (optional)</option>
            </select>
            <button type="submit">Get Bank Details</button>
        </form>

        <div id="bankDetailsSection" style="display: none;">
            <h3>Bank/Bank Details</h3>
            <ul id="bankDetailsList"></ul>
        </div>

        <form onsubmit="event.preventDefault(); updateExpiryDate()">
            <label for="update_bank_id">Bank: </label>
            <select id="update_bank_id" required onchange="populateUpdateBranchDropdown(this.value)">
                <option value="">Select a bank</option>
            </select>
            <label for="update_branch_id">Branch ID:</label>
            <select id="update_branch_id" required>
                <option value="">Select a branch</option>
            </select>
            <label for="new_expiry_date">New Expiry Date</label>
            <input type="date" id="new_expiry_date" required>
            <button type="submit">Update Expiry</button>
        </form>
        <hr>
    </div>
    <div id="allBanksSection">
        <h3>All Banks and Branches</h3>
        <button onclick="populateBankTable()" style="margin-bottom: 10px;">Refresh Table</button>
        <div class="table-container">
            <table class="bank-table">
                <thead>
                    <tr>
                        <th>Bank ID</th>
                        <th>Enum ID</th>
                        <th>Bank Name</th>
                        <th>Taluk/Town</th>
                        <th>Email</th>
                        <th>Name in Invoice</th>
                        <th>GST No</th>
                        <th>Invoice To</th>
                        <th>Bank Address</th>
                        {{-- <th>Contact Person</th> --}}
                        {{-- <th>Contact Number</th> --}}
                        <th>Customer Type</th>
                        <th>Version Type</th>
                        {{-- <th>License Expiry</th> --}}
                        {{-- <th>Business Amount</th> --}}
                        {{-- <th>Maintenance Amount</th> --}}
                        {{-- <th>Maintenance Freq</th> --}}
                        {{-- <th>Support Person</th> --}}
                        {{-- <th>Created At</th> --}}
                        {{-- <th>Updated At</th> --}}
                        <th>Branch ID</th>
                        <th>Branch Taluk/Town</th>
                        <th>Branch Address</th>
                        <th>Branch Contact Person</th>
                        <th>Branch Contact Number</th>
                        <th>Branch License Expiry</th>
                        <th>Branch Business Amount</th>
                        <th>Branch Maintenance Amount</th>
                        <th>Branch Maintenance Freq</th>
                        <th>Branch Support Person</th>
                        <th>Branch Created At</th>
                        <th>Branch Updated At</th>
                    </tr>
                </thead>
                <tbody id="bankTableBody">
                    <tr>
                        <td colspan="32">Loading...</td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>

        <script>
            // Set up Axios with Sanctum token
            // axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');

            // Populate bank dropdown
            function populateBankDropdown() {
                axios.get('/api/banks', {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    const bankSelect = document.getElementById('bank_id');
                    const updateBankSelect = document.getElementById('update_bank_id');
                    response.data.forEach(bank => {
                        const option = document.createElement('option');
                        option.value = bank.bank_id;
                        option.text = bank.bank_name || bank.bank_id;
                        bankSelect.appendChild(option.cloneNode(true));
                        updateBankSelect.appendChild(option);
                    });
                }).catch(error => {
                    alert('Failed to load banks: ' + (error.response?.data?.message || 'Unknown error'));
                    console.error('Dropdown Error:', error);
                });
            }

            // Populate branch dropdown based on bank_id
            function populateBranchDropdown(bankId) {
                const branchSelect = document.getElementById('branch_id');
                branchSelect.innerHTML = '<option value="">Select a branch (optional)</option>';
                if (bankId) {
                    axios.get('/api/banks', {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }).then(response => {
                        const bank = response.data.find(b => b.bank_id === bankId);
                        if (bank && bank.branches) {
                            bank.branches.forEach(branch => {
                                const option = document.createElement('option');
                                option.value = branch.branch_id;
                                option.text = branch.contact_person || `Branch ${branch.branch_id}`;
                                branchSelect.appendChild(option);
                            });
                        }
                    }).catch(error => {
                        alert('Failed to load branches: ' + (error.response?.data?.message || 'Unknown error'));
                        console.error('Branch Dropdown Error:', error);
                    });
                }
            }

            // Populate branch dropdown for update form
            function populateUpdateBranchDropdown(bankId) {
                const branchSelect = document.getElementById('update_branch_id');
                branchSelect.innerHTML = '<option value="">Select a branch</option>';
                if (bankId) {
                    axios.get('/api/banks', {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }).then(response => {
                        const bank = response.data.find(b => b.bank_id === bankId);
                        if (bank && bank.branches) {
                            bank.branches.forEach(branch => {
                                const option = document.createElement('option');
                                option.value = branch.branch_id;
                                option.text = branch.contact_person || `Branch ${branch.branch_id}`;
                                branchSelect.appendChild(option);
                            });
                        }
                    }).catch(error => {
                        alert('Failed to load branches: ' + (error.response?.data?.message || 'Unknown error'));
                        console.error('Update Branch Dropdown Error:', error);
                    });
                }
            }

            function getBankDetails() {
                const formData = new FormData();
                formData.append('bank_id', document.getElementById('bank_id').value);
                const branchId = document.getElementById('branch_id').value;
                if (branchId) {
                    formData.append('branch_id', branchId);
                }

                axios.post('{{ route('api.bank_details') }}', formData, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    const data = response.data;
                    const detailsList = document.getElementById('bankDetailsList');
                    detailsList.innerHTML = `
                    <li><strong>ID:</strong> ${data.bank_id}</li>
                    <li><strong>Name:</strong> ${data.bank_name}</li>
                    <li><strong>Address:</strong> ${data.address}</li>
                    <li><strong>Contact:</strong> ${data.contact_person}</li>
                    <li><strong>Expiry:</strong> ${data.license_expiry_date}</li>
                    <li><strong>Support:</strong> ${data.support_person}</li>
                `;
                    document.getElementById('bankDetailsSection').style.display = 'block';
                }).catch(error => {
                    alert('Failed to fetch details');
                    console.error(error);
                });
            }

            function updateExpiryDate() {
                const formData = new FormData();
                formData.append('bank_id', document.getElementById('update_bank_id').value);
                formData.append('branch_id', document.getElementById('update_branch_id').value);
                formData.append('expiry_date', document.getElementById('new_expiry_date').value);

                axios.post('{{ route('api.update_expiry_date') }}', formData, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    alert('Expiry date updated successfully!');
                }).catch(error => {
                    alert('Update failed');
                    console.error(error);
                });
            }

            // Fetch and display all bank and branch details
            function populateBankTable() {
                const tableBody = document.getElementById('bankTableBody');
                tableBody.innerHTML = '<tr><td colspan="32">Loading...</td></tr>';

                axios.get('/api/banks/details', {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    console.log('Bank Details Response:', response.data);
                    if (!Array.isArray(response.data) || response.data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="32">No banks or branches found.</td></tr>';
                        return;
                    }

                    tableBody.innerHTML = '';
                    response.data.forEach(bank => {
                        if (!bank.branches || bank.branches.length === 0) {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${bank.bank_id || ''}</td>
                                <td>${bank.enum_id ?? ''}</td>
                                <td>${bank.bank_name || ''}</td>
                                <td>${bank.taluk_town || ''}</td>
                                <td>${bank.email || ''}</td>
                                <td>${bank.name_in_invoice || ''}</td>
                                <td>${bank.gst_no || ''}</td>
                                <td>${bank.invoice_to || ''}</td>
                                <td>${bank.bank_address || ''}</td>
                                <td>${bank.customer_type || ''}</td>
                                <td>${bank.version_type || ''}</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            `;
                            tableBody.appendChild(row);
                        } else {
                            bank.branches.forEach((branch, index) => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${index === 0 ? (bank.bank_id || '') : ''}</td>
                                    <td>${index === 0 ? (bank.enum_id ?? '') : ''}</td>
                                    <td>${index === 0 ? (bank.bank_name || '') : ''}</td>
                                    <td>${index === 0 ? (bank.taluk_town || '') : ''}</td>
                                    <td>${index === 0 ? (bank.email || '') : ''}</td>
                                    <td>${index === 0 ? (bank.name_in_invoice || '') : ''}</td>
                                    <td>${index === 0 ? (bank.gst_no || '') : ''}</td>
                                    <td>${index === 0 ? (bank.invoice_to || '') : ''}</td>
                                    <td>${index === 0 ? (bank.bank_address || '') : ''}</td>
                                    <td>${index === 0 ? (bank.customer_type || '') : ''}</td>
                                    <td>${index === 0 ? (bank.version_type || '') : ''}</td>
                                   
                                    <td>${branch.BranchId || ''}</td>
                                    <td>${branch.taluk_town || ''}</td>
                                    <td>${branch.bank_address || ''}</td>
                                    <td>${branch.contact_person || ''}</td>
                                    <td>${branch.contact_number || ''}</td>
                                    <td>${branch.license_expiry_date || ''}</td>
                                    <td>${branch.business_amount ?? ''}</td>
                                    <td>${branch.maintenance_amount ?? ''}</td>
                                    <td>${branch.maintenance_freq || ''}</td>
                                    <td>${branch.our_support_person || ''}</td>
                                    <td>${branch.created_at || ''}</td>
                                    <td>${branch.updated_at || ''}</td>
                                `;
                                tableBody.appendChild(row);
                            });
                        }
                    });
                }).catch(error => {
                    console.error('Error fetching bank details:', {
                        status: error.response?.status,
                        message: error.response?.data?.message,
                        error: error
                    });
                    tableBody.innerHTML = '<tr><td colspan="32">Error loading data: ' + (error.response?.data
                        ?.message || 'Unknown error') + '</td></tr>';
                });
            }
        </script>

        <button class="logoutButton" onclick="logout()">Logout</button>

        <script>
            function logout() {
                localStorage.removeItem('token');
                window.location.href = '/';
            }

            // Initialize
            document.addEventListener('DOMContentLoaded', () => {
                populateBankDropdown();
            });
        </script>
    </div>
</body>

</html
