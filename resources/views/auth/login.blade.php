<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
        <div class="container">
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

                        window.location.href = '/dashboard';

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

    </script>
    </div>
</body>

</html>
