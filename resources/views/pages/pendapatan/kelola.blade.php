<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Import Income Excel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        #process-loading {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .process-box {
            text-align: center;
            padding: 24px 32px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .loader-dots span {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin: 0 4px;
            background: #0d6efd;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .loader-dots span:nth-child(1) {
            animation-delay: -0.32s;
        }

        .loader-dots span:nth-child(2) {
            animation-delay: -0.16s;
        }

        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: scale(0);
                opacity: .3;
            }

            40% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow">
            <div class="card-body">

            </div>
        </div>
    </div>

    <div id="process-loading" class="d-none">
        <div class="process-box">
            <div class="loader-dots">
                <span></span><span></span><span></span>
            </div>
            <div class="mt-2 fw-semibold">Memproses data, mohon tunggu</div>
        </div>
    </div>

</body>

</html>
