<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs Application</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style> .animate{
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
    0%   {background-color:red; left:0px; top:0px;}
    25%  {background-color:rgb(2, 2, 1); left:1100px; top:0px;color: white;}
    /* 50% {background-color:green; left:200px; top:200px;}
    75% {background-color:orangered; left:0px; top:200;}
    100% {background-color:red; left:0px; top:0px;} */

  }</style>
</head>
<body>
    <main>
    @session("success")
  
<div class="animate m-3">



{{session("success")}}
</div>
    @endsession

      {{ $slot }}
    </main>
    
</body>
</html>