<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPay - Auditoría Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 p-8">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Panel de Auditoría Global</h1>
                <p class="text-slate-500">Datos obtenidos de la Vista Segura (vw_safe_user_balances)</p>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg font-medium transition">
                Volver al Dashboard
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-sm uppercase tracking-wider text-slate-500">
                        <th class="p-4 font-semibold">ID</th>
                        <th class="p-4 font-semibold">Usuario</th>
                        <th class="p-4 font-semibold">Correo Electrónico</th>
                        <th class="p-4 font-semibold text-right">Saldo Actual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 text-slate-500 font-medium">#{{ $user->user_id }}</td>
                        <td class="p-4 font-bold text-slate-900">{{ $user->user_name }}</td>
                        <td class="p-4 text-slate-600">{{ $user->email }}</td>
                        <td class="p-4 text-right font-bold text-emerald-600">
                            ${{ number_format($user->current_balance, 2) }} <span class="text-xs text-slate-400 font-normal">{{ $user->currency }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>