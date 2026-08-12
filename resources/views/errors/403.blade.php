<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #050a06; /* Dark GameVault vibe */
            --text-color: #ffffff;
            --muted-color: #a1a1aa;
            --primary-color: #76b900; /* GameVault / NVIDIA Green */
            --shadow-color: #3b5c00;
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Press Start 2P', monospace;
            margin: 0;
            display: grid;
            place-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 20px;
            box-sizing: border-box;
            line-height: 1.5;
            background-image: radial-gradient(circle, rgba(118,185,0,0.05) 0%, transparent 60%);
        }
        .container {
            display: grid;
            gap: 20px;
            max-width: 800px;
        }
        .error-code {
            font-size: 6rem;
            margin: 0;
            color: var(--primary-color);
            text-shadow: 4px 4px 0 var(--shadow-color);
            line-height: 1.2;
        }
        @media (max-width: 600px) {
            .error-code { font-size: 4rem; }
        }
        .ogre-img {
            image-rendering: pixelated;
            width: 200px;
            height: 200px;
            margin: -30px auto 0;
            animation: ogre-roar 1.5s infinite;
            transform-origin: bottom center;
        }
        @keyframes ogre-roar {
            0%, 100% { transform: scale(1) translateY(0); filter: brightness(1); }
            20% { transform: scale(1.05) translateY(-5px); filter: brightness(1.1); }
            30%, 50%, 70% { transform: scale(1.15) translateX(-3px) rotate(-3deg); filter: brightness(1.3) sepia(0.5) hue-rotate(-50deg) saturate(3) drop-shadow(0 0 15px red); }
            40%, 60% { transform: scale(1.15) translateX(3px) rotate(3deg); filter: brightness(1.4) sepia(0.5) hue-rotate(-50deg) saturate(3) drop-shadow(0 0 15px red); }
            80% { transform: scale(1.05) translateY(-5px); filter: brightness(1.1); }
        }
        .title {
            font-size: 1.5rem;
            margin: 10px 0;
            line-height: 1.4;
        }
        @media (max-width: 600px) {
            .title { font-size: 1.2rem; }
        }
        .description {
            font-size: 0.75rem;
            color: var(--muted-color);
            margin: 0 0 20px;
            line-height: 1.8;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            color: #000;
            text-decoration: none;
            padding: 15px 30px;
            font-size: 0.8rem;
            font-family: 'Press Start 2P', monospace;
            border: 4px solid #fff;
            box-shadow: 4px 4px 0 var(--shadow-color);
            cursor: pointer;
            transition: transform 0.1s, box-shadow 0.1s;
        }
        .btn:active {
            transform: translate(4px, 4px);
            box-shadow: 0 0 0 var(--shadow-color);
        }
        .btn:hover {
            background-color: #88d400;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">403</div>
        <div style="display: flex; justify-content: center;">
            <!-- Using a similar 8-bit image or same one -->
            <img src="https://www.8bitcn.com/_next/image?url=%2Fimages%2F8bit-ogre.png&w=256&q=75&dpl=dpl_B9Q5u7DD6qZpoCz3VRwuR19npVHK" alt="Ogre" class="ogre-img">
        </div>
        <h1 class="title">You don't have the key!</h1>
        <p class="description">Only true administrators can enter this sacred ground. Turn back.</p>
        <div>
            <a href="/" class="btn">Return to Home Page</a>
        </div>
    </div>
</body>
</html>
