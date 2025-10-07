<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Compra</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 650px;
            margin: 40px auto;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: #fff;
            text-align: center;
            padding: 40px 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
        }

        .content {
            padding: 30px 40px;
        }

        .content h2 {
            color: #1e40af;
            font-size: 22px;
            margin-bottom: 10px;
        }

        .content p {
            line-height: 1.6;
            font-size: 15px;
            margin: 8px 0;
        }

        .info-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            margin-top: 25px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-item strong {
            color: #111827;
        }

        .footer {
            text-align: center;
            background: #f3f4f6;
            padding: 20px;
            font-size: 13px;
            color: #6b7280;
        }

        .highlight {
            color: #16a34a;
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            background: #2563eb;
            color: #fff !important;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
            margin-top: 15px;
            font-size: 15px;
        }

        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">

        <div class="header">
            <h1>🎉 Parabéns pela sua conquista!</h1>
        </div>

        <div class="content">
            <h2>Olá, {{ $student->name }}!</h2>
            <p>
                Sua compra foi <span class="highlight">confirmada com sucesso</span>!  
                Você acaba de adquirir o curso <strong>"{{ $course->title }}"</strong>, ministrado por
                <strong>{{ $teacher->name }}</strong>.
            </p>

            <div class="info-box">
                <div class="info-item"><strong>Curso:</strong> {{ $course->title }}</div>
                <div class="info-item"><strong>Professor:</strong> {{ $teacher->name }}</div>
                <div class="info-item"><strong>E-mail do Professor:</strong> {{ $teacher->email ?? 'Não informado' }}</div>
                <div class="info-item"><strong>Valor do Curso:</strong> R$ {{ number_format($checkout->data['price'] ?? $checkout->data['amount'] ?? 0, 2, ',', '.') }}</div>
                <div class="info-item"><strong>Método de Pagamento:</strong> {{ strtoupper($checkout->method) }}</div>
                <div class="info-item"><strong>Status:</strong> {{ ucfirst($checkout->status) }}</div>
                <div class="info-item"><strong>ID da Transação:</strong> {{ $checkout->transaction_id }}</div>
            </div>

            <p>
                Agora é só acessar a sua conta e começar o curso!
            </p>

            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/login" class="btn">Acessar minha conta</a>
            </div>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }} — Todos os direitos reservados.</p>
            <p>Esta é uma mensagem automática. Por favor, não responda este e-mail.</p>
        </div>

    </div>
</body>
</html>
