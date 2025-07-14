<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs Application</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .animate {
            width: 200px;
            height: 50px;
            background-color: rgb(128, 224, 99);
            position: relative;
            animation-name: techno;
            animation-duration: 4s;
            border-radius: 20px;
            text-align: center;
            animation-iteration-count: infinite;
        }

        @keyframes techno {
            0% {
                background-color: red;
                left: 0px;
                top: 0px;
            }

            25% {
                background-color: rgb(2, 2, 1);
                left: 1100px;
                top: 0px;
                color: white;
            }

            /* 50% {background-color:green; left:200px; top:200px;}
    75% {background-color:orangered; left:0px; top:200;}
    100% {background-color:red; left:0px; top:0px;} */

        }
    </style>
</head>

<body>
    <main>
        @if(session()->has('success'))
            <script>
                Swal.fire({
                    title: "Good job!",
                    text: "{{ session('success') }}",
                    icon: "success"
                });
            </script>
        @endif


        {{ $slot }}
    </main>

</body>

</html>