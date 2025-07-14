<!DOCTYPE html>
<html>

<head>
    <title>Photo Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <form id="upload-form" enctype="multipart/form-data">
        @csrf
        <input type="file" name="photo" id="photo" accept="image/*" required>
        <button type="submit">Upload Photo</button>
    </form>

    <div id="result"></div>

    <script>
        document.getElementById('upload-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('photo', document.getElementById('photo').files[0]);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/upload-photo', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('result').innerHTML = `
                        <p>Upload successful!</p>
                        <img src="${data.url}" alt="Uploaded photo" style="max-width: 300px;">
                        <p>URL: ${data.url}</p>
                    `;
                    } else {
                        document.getElementById('result').innerHTML = `<p>Error: ${data.message}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('result').innerHTML = '<p>Upload failed!</p>';
                });
        });
    </script>
</body>

</html>