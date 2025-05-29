<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/js/app.js'])
</head>

<body>
    <div>
        <h1>Please Login</h1>

        <form onsubmit="event.preventDefault(); submitForm()">
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div>
                <button type="submit">Login</button>
            </div>
        </form>

        <hr>

        <form onsubmit="event.preventDefault(); getBankDetails()">
            <div>
                <label>Enter Bank Id</label>
                <input type="text" id="bank_id" name="bank_id" required>
            </div>

            <div>
                <button type="submit">Get Bank Details</button>
            </div>
        </form>

        <div id="bankDetailsSection" style="margin-top: 20px; display: none;">
            <h3>Bank Details</h3>
            <ul id="bankDetailsList"></ul>
        </div>


    </div>

    <script>
        function submitForm() {
            const formData = new FormData();

            formData.append('email', document.getElementById('email').value);
            formData.append('password', document.getElementById('password').value);

            axios.post('{{ route('api.login') }}', formData)
                .then(response => {
                    // console.log('Response:', response);
                    if (response.status === 200) {
                        console.log('Login successful:', response.data);
                        localStorage.setItem('token', response.data.token);

                        // {{-- window.location.href = '{{ route('dashboard') }}'; --}}
                    }
                })
                .catch(error => {
                    if (error.status === 401) {
                        alert('Wrong email or password');
                    } else {
                        console.error('Error during login:', error);
                    }
                });
        }

        function getBankDetails() {
            const formData = new FormData();

            formData.append('bank_id', document.getElementById('bank_id').value);

            axios.post('{{ route('api.bank_details') }}', formData, {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    }
                })
                .then(response => {
                    if (response.status === 200) {
                        console.log('Get Data successful:', response.data);
                    }
                })
                .catch(error => {
                    if (error.status === 401) {
                        alert('Invalid Token');
                    } else {
                        console.error('Error during login:', error);
                    }
                });
        }
    </script>
</body>

</html>
