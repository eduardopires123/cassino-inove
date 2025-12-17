<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Presente Diário</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            background: rgba(0, 0, 0, 0.7);
            color: white;
        }

        .root-container {
            width: 100%;
            height: 100vh;
            display: flex;
            overflow-x: hidden;
            background: rgba(0, 0, 0, 0.7);
        }
        
        /* Header Styles */
        .header {
            position: fixed;
            top: 0;
            left: 250px; /* Start after sidebar width */
            width: calc(100% - 250px);
            background-color: #0c1e50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            z-index: 100; /* Lower than sidebar */
            height: 60px;
            box-sizing: border-box;
        }
        
        .header-scores {
            display: flex;
            gap: 25px; /* Reduzido de 40px */
            height: 100%;
            align-items: center;
        }
        
        .header-scores > div {
            display: flex;
            align-items: center;
        }
        
        .img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        
        .img.points {
            background-image: url('{{ asset('img/coin.png') }}');
        }
        
        .img.missions {
            background-image: url('{{ asset('img/missions.png') }}');
        }
        
        .img.levels {
            background-image: url('{{ asset('img/levels.png') }}');
        }
        
        .label {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        
        .label .amount {
            font-weight: bold;
            font-size: 18px;
            color: white;
        }
        
        .label span:last-child {
            font-size: 12px;
            color: #9da8ba;
        }
        
        .user-details {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-right: 40px;
        }
        
        .level-image {
            width: 36px;
            height: 36px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        
        .level-progress {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .to-next-level {
            font-size: 12px;
            color: #9da8ba;
            margin-bottom: 3px;
        }
        
        .to-next-level span {
            color: #ffdf1b;
            font-weight: bold;
        }
        
        .progress-container {
            width: 100%;
            margin: 3px 0;
        }
        
        .progress-bar {
            height: 5px;
            width: 200px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            overflow: hidden;
        }
        
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(to right, #ffdf1b, #ffa500);
            border-radius: 3px;
            animation: shimmer 1.5s infinite;
            background-size: 200% 100%;
        }
        
        @keyframes shimmer {
            0% {
                background-position: 100% 0;
            }
            100% {
                background-position: 0 0;
            }
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            min-width: 250px;
            height: 100vh;
            background-color: #061440;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.5);
            z-index: 101; /* Higher than header */
            position: fixed;
            left: 0;
            top: 0;
        }
        
        .menu-holder {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px 0;
        }
        
        .coin-display {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }
        
        .coin-container {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 5px 15px;
        }
        
        .coin-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }
        
        .coin-amount {
            color: #ffd700;
            font-weight: bold;
        }
        
        .user-avatar.gloss {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            margin: 0 auto;
            border: 3px solid #1258c1;
            box-shadow: 0 0 10px rgba(0, 162, 255, 0.7);
        }
        
        .ALUPs {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #1258c1;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid #061440;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            margin-right: 70px;
        }
        
        .avatar-edit-btn {
            color: white;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .menu-username {
            color: white;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
        }
        
        .edit-icon {
            font-size: 12px;
            color: #ccc;
            margin-left: 5px;
            cursor: pointer;
        }
        
        .menu-user-level {
            width: 100%;
            padding: 0 15px;
            box-sizing: border-box;
            margin-bottom: 20px;
        }
        
        .rank-container {
            display: flex;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
        }
        
        .rank-icon {
            width: 40px;
            height: 40px;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .rank-icon img {
            max-width: 100%;
            max-height: 100%;
        }
        
        .rank-info {
            flex: 1;
        }
        
        .rank-name {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .rank-name strong {
            color: white;
            margin-right: 5px;
        }
        
        .rank-name span {
            color: #ccc;
            font-size: 12px;
        }
        
        .view-levels {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #ffdf1b;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .level-icon {
            margin-right: 5px;
        }
        
        .progress-bar {
            width: 100%;
            height: 4px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(to right, #ffdf1b, #ffa500);
            border-radius: 2px;
        }
        
        .menu {
            width: 100%;
        }
        
        .menu-content {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        
        .menu-item {
            width: 100%;
            cursor: pointer;
        }
        
        a.menu-item {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .menu-item-content {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            transition: background-color 0.2s;
        }
        
        .menu-item-content:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .menu-item-content.active {
            background-color: #1e90ff;
        }
        
        .menu-icon {
            width: 24px;
            height: 24px;
            margin-right: 15px;
        }
        
        .menu-title-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .menu-title {
            color: white;
            font-size: 14px;
        }
        
        .menu-title.active {
            font-weight: bold;
        }
        
        .menu-notification {
            background-color: #ff4500;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 10px;
        }
        
        .menu-notification.active {
            background-color: #ffffff;
            color: #1e90ff;
        }
        
        .menu-arrow {
            width: 7px;
            height: 12px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='7' height='12' viewBox='0 0 7 12' fill='none'%3E%3Cpath d='M1 1L6 6L1 11' stroke='white' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
        }
        
        .menu-arrow.active {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='7' height='12' viewBox='0 0 7 12' fill='none'%3E%3Cpath d='M1 1L6 6L1 11' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        }
        
        .ach-hor-separator {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.1);
            width: 100%;
        }
        
        .withNotification {
            position: relative;
        }

        .content {
            padding-top: 60px; /* Same as header height */
            width: calc(100% - 250px);
            min-height: calc(100vh - 60px);
            height: auto;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-left: 20px;
            padding-right: 20px;
            box-sizing: border-box;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            width: 100%;
            max-width: 600px;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            margin-right: 15px;
        }

        .user-details {
            flex: 1;
        }

        .user-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .user-level {
            font-size: 14px;
            color: #ccc;
        }

        .game-container {
            width: 100%;
            max-width: 900px;
            background-image: url('https://dvm0p9vsezqr2.cloudfront.net/resize/0/0/webp/https://d146b4m7rkvjkw.cloudfront.net/0cbabdf6-b66c-429e-87a8-1384834b39fc/game-bg.png');
            background-size: cover;
            background-position: center;
            border-radius: 15px;
            padding: 20px;
            box-sizing: border-box;
        }

        .game-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .game-title {
            font-size: 36px;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 15px;
            background: linear-gradient(to right, #ffffff, #72c5ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0px 4px 15px rgba(0, 81, 255, 0.8);
            letter-spacing: 1px;
            display: inline-block;
        }

        .game-description {
            font-size: 18px;
            margin-bottom: 20px;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);
            background: rgba(0, 0, 0, 0.2);
            padding: 10px 15px;
            border-radius: 20px;
            display: inline-block;
        }

        .game-description .emoji {
            font-size: 22px;
            vertical-align: middle;
            margin: 0 3px;
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
            max-width: 100%;
        }

        .prize-card {
            position: relative;
            width: 120px;
            height: 180px;
            perspective: 1000px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .prize-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        }

        .prize-card-content {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .prize-card.flip .prize-card-content {
            transform: rotateY(180deg);
        }

        .front-side, .back-side {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 10px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .back-side {
            transform: rotateY(180deg);
        }

        .prize-number {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
            color: #fff;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.7);
            background-color: rgba(0, 133, 255, 0.3);
            padding: 2px 10px;
            border-radius: 15px;
        }

        .prize-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .prize-front-image {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }

        .prize-front-prize-name {
            font-size: 14px;
            text-align: center;
            margin-bottom: 8px;
            font-weight: bold;
            color: #fff;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.7);
            background: rgba(0, 53, 122, 0.5);
            padding: 3px 8px;
            border-radius: 8px;
            max-width: 90%;
        }

        .prize-card-bottom-glow {
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 10px;
            border-radius: 0 0 10px 10px;
            background: #03c6fc;
            z-index: -1;
            box-shadow: 0 0 15px 5px rgba(3, 198, 252, 0.5);
        }

        .missed-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            z-index: 1;
        }

        .game-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .game-nav-buttons {
            display: flex;
            gap: 10px;
        }

        .game-nav-btn, .game-rules-btn {
            background: #ffe41f;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .game-rules-btn {
            background: #c60000;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
            padding: 10px 20px;
        }

        .game-nav-arrow {
            width: 20px;
            height: 20px;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .game-nav-arrow.left {
            transform: rotate(180deg);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);
        }

        .modal-content {
            background-color: #0a1a3a;
            margin: 15% auto;
            padding: 25px;
            border: 1px solid #2c4b7a;
            width: 80%;
            max-width: 500px;
            border-radius: 15px;
            text-align: center;
            color: white;
            box-shadow: 0 0 25px rgba(0, 140, 255, 0.5);
        }

        .close {
            color: #6e9fff;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        #premio-info {
            margin: 20px 0;
        }

        #premio-imagem {
            max-width: 100%;
            height: auto;
            max-height: 200px;
        }

        #premio-nome {
            background: linear-gradient(to right, #ffffff, #72c5ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0px 2px 4px rgba(0, 81, 255, 0.5);
            font-size: 20px;
        }

        #fechar-premio {
            background: linear-gradient(to right, #0066cc, #00a1ff);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Estados específicos das cartas */
        .prize-card.missed .prize-card-content {
            opacity: 0.7;
        }
        
        .prize-card.active-prize {
            transform: scale(1.05);
            z-index: 10;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.7);
        }

        .prize-card.active-prize.claimed .prize-card-bottom-glow {
            background: #4CAF50;
            box-shadow: 0 0 15px 5px rgba(76, 175, 80, 0.5);
        }
        
        .prize-card.active-prize .prize-card-bottom-glow {
            box-shadow: 0 0 15px 5px rgba(255, 215, 0, 0.6);
            background: linear-gradient(to right, #ffb300, #ffd700);
            animation: glowPulse 1.5s infinite alternate;
        }
        
        @keyframes glowPulse {
            from {
                opacity: 0.7;
                box-shadow: 0 0 10px 2px rgba(255, 215, 0, 0.5);
            }
            to {
                opacity: 1;
                box-shadow: 0 0 20px 5px rgba(255, 215, 0, 0.8);
            }
        }

        /* Responsividade */
        @media (max-width: 992px) {
            .root-container {
                flex-direction: column;
            }
            
            .header {
                left: 0;
                width: 100%;
                flex-direction: row;
                height: 60px;
                padding: 5px 15px;
                z-index: 103; /* Higher than sidebar on mobile */
                position: relative;
            }
            
            .sidebar {
                position: relative;
                width: 100%;
                min-width: 100%;
                height: auto;
                max-height: 300px;
                top: auto;
                left: auto;
                z-index: 102;
            }
            
            .content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            .content {
                margin-left: 0;
                padding-top: 0;
                width: 100%;
                padding: 20px;
                height: auto;
            }
            
            .header-scores {
                width: auto;
                gap: 20px;
                margin-bottom: 0;
            }
            
            .user-details {
                width: auto;
                justify-content: flex-end;
            }
            
            .close-button {
                top: 15px;
                right: 15px;
            }

            .cards-container {
                max-width: 100%;
                overflow-x: auto;
                justify-content: flex-start;
                flex-wrap: nowrap;
                padding: 10px 0;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
                scroll-behavior: smooth;
                scroll-snap-type: x mandatory;
                padding-bottom: 20px;
            }

            .prize-card {
                scroll-snap-align: start;
                flex: 0 0 auto;
                margin-right: 5px;
            }
        }
        
        @media (max-width: 768px) {
            .header-scores {
                gap: 15px;
            }
            
            .progress-bar {
                width: 150px;
            }
            
            .cards-container {
                gap: 10px;
            }

            .prize-card {
                width: 100px;
                height: 150px;
            }

            .game-title {
                font-size: 28px;
            }

            .game-description {
                font-size: 16px;
            }
            
            .sidebar {
                max-height: 250px;
            }
            
            .label .amount {
                font-size: 16px;
            }

            .game-container {
                width: 95%;
                padding: 15px;
            }

            .game-description {
                max-width: 100%;
                font-size: 14px;
                padding: 8px 12px;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 5px 10px;
            }
            
            .header-scores {
                gap: 10px;
            }
            
            .img {
                margin-right: 5px;
            }
            
            .label .amount {
                font-size: 14px;
            }
            
            .label span:last-child {
                font-size: 10px;
            }
            
            .to-next-level {
                font-size: 10px;
            }
            
            .progress-bar {
                width: 120px;
            }
            
            .prize-card {
                width: 80px;
                height: 120px;
            }

            .prize-number {
                font-size: 12px;
            }

            .prize-front-prize-name {
                font-size: 10px;
            }
            
            .sidebar {
                max-height: 200px;
            }
            
            .close-button {
                top: 10px;
                right: 10px;
            }

            .game-title {
                font-size: 24px;
            }

            .game-description {
                font-size: 12px;
                padding: 6px 10px;
            }

            .game-footer {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }

            .game-nav-buttons {
                order: 2;
            }

            .game-rules-btn {
                order: 1;
                width: 100%;
                text-align: center;
            }
        }

        .close-button {
            position: absolute;
            top: 15px;
            right: 15px;
            cursor: pointer;
            z-index: 105; /* Higher than sidebar and header */
        }
        
        .close-button a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .close-button a:hover {
            background-color: rgba(0, 0, 0, 0.7);
            transform: scale(1.1);
        }
        
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: 250px; /* Same as sidebar width */
            width: calc(100% - 250px);
            min-height: 100vh;
            height: auto;
        }
        
        .content {
            padding-top: 60px; /* Same as header height */
            height: calc(100vh - 60px);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-left: 20px;
            padding-right: 20px;
            box-sizing: border-box;
            width: 100%;
        }

        /* Estilos para a barra de rolagem horizontal */
        .cards-container::-webkit-scrollbar {
            height: 6px;
        }
        
        .cards-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        .cards-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
        
        .cards-container::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>
    <div class="root-container">
        <div class="sidebar">
            <div class="menu-holder">
                <div style="position: relative; margin-top: 20px;">
                    <div class="user-avatar gloss" style="background-image: url('{{ $user->image ? $user->image : 'https://dvm0p9vsezqr2.cloudfront.net/resize/128/128/webp/https://d146b4m7rkvjkw.cloudfront.net/936c13ec-f098-4779-94fa-a98480a52238/16.png' }}');"></div>
                    <div class="ALUPs" role="none">
                        <span class="inove-icon inove-icon--fill avatar-edit-btn" id="avatar-edit-btn">
                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.8 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="menu-username change-allowed">{{ explode(' ', $user->name)[0] }}
                    <span class="inove-icon inove-icon--fill edit-icon">
                        <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.8 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"
                                fill="currentColor"
                            ></path>
                        </svg>
                    </span>
                </div>
                <div class="menu-user-level">
                    <div class="rank-container">
                        <div class="rank-icon">
                            <img draggable="false" src="{{ asset($ranking['image']) }}" alt="{{ $ranking['name'] }}" />
                        </div>
                        <div class="rank-info">
                            <div class="rank-name">
                                <strong>{{ $ranking['name'] }}</strong>
                                <span>Nível {{ $ranking['level'] }}</span>
                            </div>
                            <a href="{{ route('vip.levels') }}" class="view-levels">
                                <span class="level-icon">
                                    <svg fill="#ffdf1b" height="1em" stroke="#ffdf1b" viewBox="0 0 140.599 140.599" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <g fill="#ffdf1b">
                                            <path
                                                d="M132.861,56.559c-4.27,0-7.742,3.473-7.742,7.741c0,1.893,0.685,3.626,1.815,4.973l-15.464,15.463 c-2.754,2.754-4.557,1.857-4.027-2l0.062-0.445c0.528-3.857-1.39-4.876-4.286-2.273l-0.531,0.479 c-2.898,2.603-5.828,1.609-6.544-2.219l-5.604-29.964c-0.717-3.828-2.129-3.886-3.156-0.129l-7.023,25.689 c-1.025,3.757-2.295,3.677-2.834-0.181L71.93,33.674c3.488-0.751,6.111-3.856,6.111-7.566c0-4.268-3.473-7.741-7.741-7.741 c-4.269,0-7.742,3.473-7.742,7.741c0,3.709,2.625,6.815,6.112,7.566l-5.592,40.019c-0.539,3.857-1.809,3.938-2.835,0.181 l-7.023-25.69c-1.027-3.757-2.44-3.699-3.156,0.129l-5.605,29.964c-0.716,3.828-3.645,4.82-6.543,2.219l-0.533-0.479 c-2.897-2.604-4.816-1.586-4.287,2.272l0.061,0.445c0.529,3.858-1.274,4.753-4.028,2L13.667,69.273 c1.132-1.347,1.816-3.08,1.816-4.973c0-4.269-3.473-7.741-7.741-7.741C3.473,56.559,0,60.032,0,64.3 c0,4.269,3.473,7.742,7.742,7.742c0.478,0,0.942-0.05,1.396-0.132l10.037,33.949c1.104,3.734,3.534,9.637,7.161,11.055 c8.059,3.153,24.72,5.318,43.964,5.318c19.245,0,35.905-2.165,43.965-5.318c3.626-1.418,6.058-7.32,7.161-11.055l10.037-33.949 c0.453,0.083,0.918,0.132,1.396,0.132c4.268,0,7.739-3.473,7.739-7.742C140.6,60.032,137.127,56.559,132.861,56.559z"
                                                fill="#ffdf1b"
                                            ></path>
                                        </g>
                                    </svg>
                                </span>
                                <span class="view-levels-text">Ver níveis</span>
                            </a>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $progress ?? '0' }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="menu">
                    <div class="menu-content">
                        <a href="/" class="menu-item">
                            <div class="menu-item-content">
                                <img src="{{ asset('img/home.png') }}" class="menu-icon">
                                <div class="menu-title-container">
                                    <div class="menu-title">Página Inicial</div>
                                </div>
                                <div class="menu-arrow"></div>
                            </div>
                            <div class="ach-hor-separator"></div>
                        </a>
                        <a href="/missoes" class="menu-item">
                            <div class="menu-item-content withNotification">
                                <img src="{{ asset('img/missions.png') }}" class="menu-icon">
                                <div class="menu-title-container">
                                    <div class="menu-title">Missões</div>
                                    <div class="menu-notification">+24</div>
                                </div>
                                <div class="menu-arrow"></div>
                            </div>
                            <div class="ach-hor-separator"></div>
                        </a>
                        <a href="/torneios" class="menu-item">
                            <div class="menu-item-content withNotification">
                                <img src="{{ asset('img/tournaments.png') }}" class="menu-icon">
                                <div class="menu-title-container">
                                    <div class="menu-title">Torneios</div>
                                    <div class="menu-notification">+4</div>
                                </div>
                                <div class="menu-arrow"></div>
                            </div>
                            <div class="ach-hor-separator"></div>
                        </a>
                        <a href="/ranking" class="menu-item">
                            <div class="menu-item-content">
                                <img src="{{ asset('img/leaderboard.png') }}" class="menu-icon">
                                <div class="menu-title-container">
                                    <div class="menu-title">Ranking do Clube</div>
                                </div>
                                <div class="menu-arrow"></div>
                            </div>
                            <div class="ach-hor-separator"></div>
                        </a>
                        <a href="/niveis" class="menu-item">
                            <div class="menu-item-content">
                                <img src="{{ asset('img/levels.png') }}" class="menu-icon">
                                <div class="menu-title-container">
                                    <div class="menu-title">Níveis</div>
                                </div>
                                <div class="menu-arrow"></div>
                            </div>
                            <div class="ach-hor-separator"></div>
                        </a>
                        <a href="/mini-jogos" class="menu-item">
                            <div class="menu-item-content withNotification">
                                <img src="{{ asset('img/saw.png') }}" class="menu-icon">
                                <div class="menu-title-container">
                                    <div class="menu-title">Mini Jogos</div>
                                    <div class="menu-notification">+1</div>
                                </div>
                                <div class="menu-arrow"></div>
                            </div>
                            <div class="ach-hor-separator"></div>
                        </a>
                        <a href="/presente-diario" class="menu-item active">
                            <div class="menu-item-content active">
                                <img src="https://d146b4m7rkvjkw.cloudfront.net/90257ba7199ccc6ffb1717-Presente.png" class="menu-icon active">
                                <div class="menu-title-container">
                                    <div class="menu-title active">Presente Diário</div>
                                </div>
                                <div class="menu-arrow active"></div>
                            </div>
                            <div class="ach-hor-separator"></div>
                        </a>
                        <a href="/loja" class="menu-item">
                            <div class="menu-item-content withNotification">
                                <img src="{{ asset('img/store.png') }}" class="menu-icon">
                                <div class="menu-title-container">
                                    <div class="menu-title">Loja</div>
                                    <div class="menu-notification">+21</div>
                                </div>
                                <div class="menu-arrow"></div>
                            </div>
                            <div class="ach-hor-separator"></div>
                        </a>
                        <a href="/caixa-entrada" class="menu-item">
                            <div class="menu-item-content withNotification">
                                <img src="{{ asset('img/activity.png') }}" class="menu-icon">
                                <div class="menu-title-container">
                                    <div class="menu-title">Caixa de Entrada</div>
                                    <div class="menu-notification">+1</div>
                                </div>
                                <div class="menu-arrow"></div>
                            </div>
                            <div class="ach-hor-separator"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="header">
                <div class="header-scores">
                    <div class=" ">
                        <div class="img points"></div>
                        <div class="label">
                            <span class="amount">{{ Auth::user()->wallet->coin ?? 0 }}</span>
                            <span>Coins</span>
                        </div>
                    </div>
                    <div class=" ">
                        <div class="img missions"></div>
                        <div class="label">
                            <span class="amount">{{ Auth::user()->completed_missions ?? 0 }}</span>
                            <span>Missões</span>
                        </div>
                    </div>
                    <div class=" ">
                        <div class="img levels"></div>
                        <div class="label">
                            <span class="amount">{{ $ranking['level'] ?? 1 }}</span>
                            <span>Nível</span>
                        </div>
                    </div>
                </div>
                <div class="user-details">
                    <div class="level-image" style="background-image: url('{{ asset($ranking['image'] ?? '') }}');"></div>
                    <div class="level-progress">
                        <div class="to-next-level">O próximo nível é <span>{{ $nextRanking['name'] ?? 'Bronze 2' }}</span></div>
                        <div class="progress-container user-progress">
                            <div class="progress-bar undefined">
                                <div class="progress-bar-fill user-progress-fill" style="width: {{ $progress ?? 0 }}%;"></div>
                            </div>
                        </div>
                        <div class="to-next-level"><span>{{ $pointsToNextLevel ?? 400 }}</span> pontos para chegar lá</div>
                    </div>
                </div>
                <div class="close-button">
                    <a href="javascript:void(0);" id="header-close-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="currentColor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="content">
                <div class="game-container">
                    <div class="game-header">
                        <h1 class="game-title">Presente Diário</h1>
                        <div class="game-description"><span class="emoji">👉</span> Todos os dias terá uma cartinha para você virar e concorrer a prêmios incríveis! <span class="emoji">👀</span></div>
                    </div>

                    <div class="cards-container">
                        @foreach($dias as $dia)
                        <div class="prize-card {{ $dia['claimed'] ? 'active-prize flip claimed' : ($dia['missed'] ? 'missed' : ($dia['is_today'] ? 'active-prize' : '')) }}">
                            <div class="prize-card-content {{ $dia['claimed'] ? 'active-prize flip claimed' : ($dia['missed'] ? 'missed' : ($dia['is_today'] ? 'active-prize' : '')) }}" 
                                 @if($dia['is_today'] && !$dia['claimed']) data-date="{{ $dia['data_completa'] }}" onclick="abrirCarta(this)" @endif>
                                <div class="front-side" style="background-image: url('https://dvm0p9vsezqr2.cloudfront.net/resize/0/0/webp/https://d146b4m7rkvjkw.cloudfront.net/0cbabdf6-b66c-429e-87a8-1384834b39fc/card-front-bg.png');">
                                    <div class="prize-number">{{ $dia['data'] }}</div>
                                                                    </div>
                                <div class="back-side" style="background-image: url('https://dvm0p9vsezqr2.cloudfront.net/resize/0/0/webp/https://d146b4m7rkvjkw.cloudfront.net/0cbabdf6-b66c-429e-87a8-1384834b39fc/card-back-bg.png');">
                                    @if($dia['missed'])
                                    <div class="missed-overlay" style="background-image: url('https://dvm0p9vsezqr2.cloudfront.net/resize/0/0/webp/https://d146b4m7rkvjkw.cloudfront.net/0cbabdf6-b66c-429e-87a8-1384834b39fc/missed.png');"></div>
                                    @endif
                                                                    <div class="prize-content">
                                        <div class="prize-front-prize-name">{{ $dia['claimed'] ? $dia['premio_nome'] : '' }}</div>
                                        <img class="prize-front-image" src="{{ $dia['premio_imagem'] }}" alt="prize-icon" draggable="false" />
                                    </div>
                                    <div class="prize-number">{{ $dia['data'] }}</div>
                                </div>
                                <div class="prize-card-bottom-glow {{ $dia['claimed'] ? 'active-prize claimed' : '' }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="game-footer">
                        <div class="game-nav-buttons">
                            <div class="game-nav-btn left"><div class="game-nav-arrow left" style="background-image: url('/gf/images/lootbox-imgs/nav-arrow.png');"></div></div>
                            <div class="game-nav-btn right"><div class="game-nav-arrow right" style="background-image: url('/gf/images/lootbox-imgs/nav-arrow.png');"></div></div>
                        </div>
                        <div class="game-rules-btn">Regras</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="premio-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 style="color: #ffcc00; font-size: 28px; margin-bottom: 10px; text-shadow: 0 2px 5px rgba(0,0,0,0.3);">Parabéns!</h2>
            <p style="font-size: 16px; color: #e0e0e0; margin-bottom: 20px;">Você recebeu um prêmio incrível!</p>
            <div id="premio-info">
                <img id="premio-imagem" src="" alt="Imagem do Prêmio">
                <h3 id="premio-nome"></h3>
            </div>
            <button id="fechar-premio">Fechar</button>
        </div>
    </div>

    <script>
        function abrirCarta(elemento) {
            const data = elemento.getAttribute('data-date');
            
            // Adiciona classe para virar a carta
            elemento.classList.add('flip');
            
            // Envia requisição para o servidor registrar o prêmio recebido
            fetch('{{ route("minigames.presente-diario.receber") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ data: data })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Exibe o modal com as informações do prêmio
                    document.getElementById('premio-imagem').src = data.premio.imagem;
                    document.getElementById('premio-nome').textContent = data.premio.nome;
                    document.getElementById('premio-modal').style.display = 'block';
                    
                    // Adiciona classe claimed para mostrar que o prêmio foi recebido
                    elemento.classList.add('claimed');
                    elemento.parentNode.classList.add('claimed');
                    
                    // Mostra o nome do prêmio na carta
                    const prizeName = elemento.querySelector('.prize-front-prize-name');
                    if (prizeName) {
                        prizeName.textContent = data.premio.nome;
                    }
                    
                    // Adiciona a classe active-prize e claimed
                    elemento.classList.add('active-prize', 'claimed');
                    elemento.parentNode.classList.add('active-prize', 'claimed');
                    
                    // Ativa o glow da carta
                    const glow = elemento.querySelector('.prize-card-bottom-glow');
                    if (glow) {
                        glow.classList.add('active-prize', 'claimed');
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Ocorreu um erro ao processar sua solicitação.');
            });
        }
        
        // Fecha o modal quando o usuário clica no X
        document.querySelector('.close').addEventListener('click', function() {
            document.getElementById('premio-modal').style.display = 'none';
        });
        
        // Fecha o modal quando o usuário clica no botão Fechar
        document.getElementById('fechar-premio').addEventListener('click', function() {
            document.getElementById('premio-modal').style.display = 'none';
        });
        
        // Fecha o modal quando o usuário clica fora dele
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('premio-modal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });

        // Navegação entre as cartas
        document.querySelector('.game-nav-btn.left').addEventListener('click', function() {
            const container = document.querySelector('.cards-container');
            container.scrollBy({ left: -300, behavior: 'smooth' });
        });

        document.querySelector('.game-nav-btn.right').addEventListener('click', function() {
            const container = document.querySelector('.cards-container');
            container.scrollBy({ left: 300, behavior: 'smooth' });
        });

        document.querySelector('.game-rules-btn').addEventListener('click', function() {
            // Mostrar regras do jogo
            alert('Regras do Presente Diário:\n\n1. Todos os dias você pode abrir um presente\n2. Clique na carta do dia atual para revelar seu prêmio\n3. Você só pode abrir a carta do dia atual\n4. Os prêmios não reclamados de dias anteriores são perdidos');
        });

        // Verifica o tamanho da tela e ajusta o layout se necessário
        function ajustarLayout() {
            const container = document.querySelector('.cards-container');
            const isMobile = window.innerWidth <= 992;
            
            if (isMobile) {
                container.style.flexWrap = 'nowrap';
                container.style.justifyContent = 'flex-start';
                container.style.overflowX = 'auto';
            } else {
                container.style.flexWrap = 'wrap';
                container.style.justifyContent = 'center';
                container.style.overflowX = 'visible';
            }
        }

        // Chama a função no carregamento e quando a tela é redimensionada
        window.addEventListener('load', ajustarLayout);
        window.addEventListener('resize', ajustarLayout);

        // Handle avatar edit button click
        document.getElementById('avatar-edit-btn').addEventListener('click', function() {
            // Redirect to profile page or open avatar edit modal
            window.location.href = '{{ route("user.account") }}';
        });
        
        // Handle username edit icon click
        document.querySelector('.edit-icon').addEventListener('click', function() {
            // Redirect to profile edit page
            window.location.href = '{{ route("user.account") }}';
        });
        
        // Handle header close button click
        document.getElementById('header-close-btn').addEventListener('click', function() {
            // Go back to previous page
            window.history.back();
        });
    </script>
</body>
</html>
