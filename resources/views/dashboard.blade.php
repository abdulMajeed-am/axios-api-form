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
        <h1>Dashboard</h1>

        <form onsubmit="event.preventDefault(); getBankDetails()">
            <label>Bank ID</label>
            <input type="text" id="bank_id" required>
            <button type="submit">Get Bank Details</button>
        </form>

        <div id="bankDetailsSection" style="display: none;">
            <h3>Bank Details</h3>
            <ul id="bankDetailsList"></ul>
        </div>

        <form onsubmit="event.preventDefault(); updateExpiryDate()">
            <label>Bank ID</label>
            <input type="text" id="update_bank_id" required>
            <label>New Expiry Date</label>
            <input type="date" id="new_expiry_date" required>
            <button type="submit">Update Expiry</button>
        </form>

        <script>
            function getBankDetails() {
                const formData = new FormData();
                formData.append('bank_id', document.getElementById('bank_id').value);

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
        </script>

        <button class="logoutButton" onclick="logout()">Logout</button>

        <script>
            function logout() {
                localStorage.removeItem('token');
                window.location.href = '/';
            }
        </script>
    </div>
</body>

</html>
