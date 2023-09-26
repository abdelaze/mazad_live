<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verified</title>
    <style lang="scss">
        @keyframes trophy {
            0% {
                transform: translateY(500px);
                opacity: 0;
            }
            35% {
                opacity: 0;
            }
            100% {
                transform: translateY(0px);
                opacity: 1;
            }
        }

        @keyframes fly--up {
            0% {
                transform: translateY(10px);
                opacity: 0;
            }
            60% {
                opacity: 1;
            }
            80% {
                transform: translateY(-20vw);
            }
            100% {
                transform: translateY(-15vw);
                opacity: 0;
            }
        }

        @keyframes fly--down {
            0% {
                transform: translateY(-10px);
                opacity: 0;
            }
            60% {
                opacity: 1;
            }
            80% {
                transform: translateY(20vw);
            }
            100% {
                transform: translateY(15vw);
                opacity: 0;
            }
        }

        @keyframes fly--left {
            0% {
                transform: translateX(10px);
                opacity: 0;
            }
            60% {
                opacity: 1;
            }
            80% {
                transform: translateX(-35vw);
            }
            100% {
                transform: translateX(-180px);
                opacity: 0;
            }
        }

        @keyframes fly--right {
            0% {
                transform: translateX(-10px);
                opacity: 0;
            }
            60% {
                opacity: 1;
            }
            80% {
                transform: translateX(35vw);
            }
            100% {
                transform: translateX(180px);
                opacity: 0;
            }
        }

        @keyframes fly--up--left {
            0% {
                transform: rotate(135deg) translate(0vw,0vw);
                opacity: 0;
            }
            60% {
                opacity: 1;
            }
            100% {
                transform: rotate(135deg) translate(-3vw,20vw);
                opacity: 0;
            }
        }

        @keyframes fly--up--right {
            0% {
                transform: rotate(45deg);
                opacity: 0;
            }
            60% {
                opacity: 1;
            }
            100% {
                transform: rotate(45deg) translate(-3vw,-20vw);
                opacity: 0;
            }
        }

        @keyframes fly--down--left {
            0% {
                transform: rotate(45deg) translate(0vw,0vw);
                opacity: 0;
            }
            60% {
                opacity: 1;
            }
            100% {
                transform: rotate(45deg) translate(-3vw,20vw);
                opacity: 0;
            }
        }

        @keyframes fly--down--right {
            0% {
                transform: rotate(135deg) translate(0vw,0vw);
                opacity: 0;
            }
            60% {
                opacity: 1;
            }
            100% {
                transform: rotate(135deg) translate(0vw,-20vw);
                opacity: 0;
            }
        }

        html,body {
            padding: 0;
            margin: 0;
        }

        body {
            background-color: #8CE742;
        }

        .container {
            overflow: hidden;
            position: relative;
            width: 100vw;
            height: 93vh;
            outline: solid 1px #8CE742;
        }

        .trophy {
            z-index: 1;
            background-color: #8CE742;
            height: 100%;
            width: 100%;
            border-radius: 100%;
            animation: trophy 0.5s 1 forwards;
        }

        .Verify_logo {
            width: 200px;
            height: 200px;
        }

        .action {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);

        /*  max-height: 300px;
            height: 25vw;
            max-width: 300px;
            width: 25vw;       */

        %confetti {
             z-index: -1;
             position: absolute;
             height: 50px;
             width: 10px;
             border-radius: 10px;
             animation-fill-mode: forwards;
             animation-duration: 0.75s;
             animation-iteration-count: 1;
             transform-origin: center middle;
             opacity: 0;
         }
        .confetti {
            @extend %confetti;
            top: 0;
            left: calc(50% - 5px);
            background-color: #FFEA00;
            animation-name: fly--up;
            animation-delay: 0.35s;
        &.two {
             top: auto;
             bottom: 0;
             animation-name: fly--down;
         }
        &.three,&.four {
                     top: calc(50% - 5px);
                     left: calc(50% - 25px);
                     height: 10px;
                     width: 50px;
                     animation-name: fly--left;
                 }
        &.four {
             animation-name: fly--right;
         }
        &--purple {
             @extend %confetti;
             background-color: #6200EA;
             animation-name: fly--up--left;
             transform: rotate(135deg);
             animation-delay: .5s;
             left: 20%;
             top: 20%;
        &.two {
             animation-name: fly--up--right;
             left: auto;
             right: 20%;
             transform: rotate(45deg);
         }
        &.three {
             top: auto;
             bottom: 20%;
             transform: rotate(45deg);
             animation-name: fly--down--left;
         }
        &.four {
             top: auto;
             bottom: 20%;
             left: auto;
             right: 20%;
             transform: rotate(135deg);
             animation-name: fly--down--right;
         }
        }
        }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="action">
        <div class="trophy">
            <img class="Verify_logo" src="{{asset('public/assets/images/logos/Verify_Logo.png')}}">
            <h3 style="text-align: center;color: white;">Activated successfully</h3>
        </div>
        <div class="confetti"></div>
        <div class="confetti two"></div>
        <div class="confetti three"></div>
        <div class="confetti four"></div>
        <div class="confetti--purple"></div>
        <div class="confetti--purple two"></div>
        <div class="confetti--purple three"></div>
        <div class="confetti--purple four"></div>
    </div>
</div>
</body>
</html>
