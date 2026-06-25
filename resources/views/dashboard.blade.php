<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPay - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col md:flex-row">

    <!-- BARRA LATERAL -->
    <aside class="w-full md:w-64 bg-slate-900 text-slate-300 flex flex-col">
        <div class="p-6 flex items-center gap-3">
            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center text-white font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-xl font-bold text-white tracking-wide"></span>
        </div>
        
        <div class="p-4 mt-auto">
            <!-- Formulario para cerrar sesión (Vanilla Breeze) -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 text-slate-400 hover:text-white transition px-4 py-2 w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="font-medium">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ÁREA PRINCIPAL -->
    <main class="flex-1 p-4 md:p-8 overflow-y-auto">
        
        <!-- Cabecera Dinámica -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Hola, {{ auth()->user()->name }}</h1>
                <p class="text-sm text-slate-500">Bienvenido de vuelta a tu panel transaccional.</p>
            </div>
            <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center font-bold text-slate-600 border-2 border-white shadow-sm uppercase">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                <!-- Tarjeta de Saldo Dinámica -->
                <div class="bg-gradient-to-br from-slate-900 to-indigo-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-20">
                        <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    </div>
                    <p class="text-indigo-200 font-medium mb-1 relative z-10">Saldo Disponible</p>
                    <h2 class="text-5xl font-bold tracking-tight relative z-10">
                        ${{ number_format($wallet->balance, 2) }} <span class="text-xl font-normal text-indigo-300">{{ $wallet->currency }}</span>
                    </h2>
                </div>

                <!-- Formulario de Transferencia conectado al Backend -->
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Transferir Dinero</h3>
                            <p class="text-sm text-slate-500">Operación protegida por DB Transactions (ACID)</p>
                        </div>
                    </div>

                    <!-- Mensajes de Sesión de Laravel (Éxito o Error del Trigger) -->
                    @if(session('error'))
                        <div class="mb-6 p-4 rounded-xl text-sm font-medium fade-in flex items-start gap-3 bg-red-50 text-red-700 border border-red-100">
                            <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span><strong>Error:</strong> {{ session('error') }}</span>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl text-sm font-medium fade-in flex items-start gap-3 bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-xl text-sm font-medium fade-in bg-red-50 text-red-700 border border-red-100">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('transfer.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Correo del Destinatario</label>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="usuario@ejemplo.com" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition bg-slate-50">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Monto a Enviar ($)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-slate-400 font-medium">$</span>
                                </div>
                                <input type="number" name="amount" required min="0.01" step="0.01" value="{{ old('amount') }}" placeholder="0.00" class="w-full pl-8 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition text-lg font-semibold bg-slate-50">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3.5 rounded-xl transition shadow-md shadow-indigo-200 flex justify-center items-center gap-2">
                            <span>Confirmar Transferencia</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-slate-100 rounded-lg text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">Retirar Fondos</h3>
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Comisión: 3%</p>
                    </div>
                </div>

                <form id="withdrawForm" onsubmit="event.preventDefault();" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Monto a retirar ($)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-mono">$</div>
                            <input type="number" id="withdrawAmount" required min="1" step="0.01" 
                                oninput="updateWithdrawPreview()"
                                placeholder="0.00" 
                                class="w-full pl-8 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-slate-900 outline-none transition bg-slate-50 font-mono font-bold">
                        </div>
                    </div>

                    <!-- Panel Técnico de Vista Previa -->
                    <div id="withdrawPreview" class="hidden space-y-3 bg-slate-900 text-white p-4 rounded-xl font-mono text-sm">
                        <div class="flex justify-between border-b border-slate-700 pb-2">
                            <span class="text-slate-400">Comisión (3%):</span>
                            <span id="feeResult">-$0.00</span>
                        </div>
                        <div class="flex justify-between pt-1">
                            <span class="text-slate-300 font-bold">Total Neto:</span>
                            <span id="netResult" class="text-indigo-400 font-bold">$0.00</span>
                        </div>
                    </div>

                    <button type="submit" 
                            id="btnRetiro"
                            onclick="procesarRetiro(this)"
                            class="w-full bg-slate-800 hover:bg-slate-950 text-white font-semibold py-3.5 rounded-xl transition shadow-lg flex justify-center items-center gap-2 disabled:opacity-50">
                        <span id="btnText">Solicitar Retiro</span>
                    </button>
                </form>
            </div>

            <!-- Columna Derecha (Historial Dinámico) -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold">Actividad Reciente</h3>
                </div>

                <div class="space-y-5 flex-1 overflow-y-auto max-h-[500px]">
                    @forelse($transactions as $tx)
                        @php
                            // Lógica para saber si el dinero entró o salió
                            $isSender = $tx->sender_wallet_id == $wallet->id;
                            $amountClass = $isSender ? 'text-slate-900' : 'text-emerald-600';
                            $amountPrefix = $isSender ? '-' : '+';
                            $title = $isSender ? 'Transferencia enviada' : 'Dinero recibido';
                        @endphp
                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition border border-transparent hover:border-slate-100">
                            <div class="flex items-center gap-4">
                                @if($isSender)
                                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7"></path></svg>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-sm">{{ $title }}</p>
                                    <p class="text-xs text-slate-500">{{ $tx->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="font-bold {{ $amountClass }}">{{ $amountPrefix }}${{ number_format($tx->amount, 2) }}</span>
                        </div>
                    @empty
                        <div class="text-center text-slate-500 text-sm py-4">
                            No hay transacciones aún.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</body>
<script>
    function updateWithdrawPreview() {
        const amount = parseFloat(document.getElementById('withdrawAmount').value) || 0;
        const preview = document.getElementById('withdrawPreview');
        
        if (amount > 0) {
            preview.classList.remove('hidden');
            const fee = amount * 0.03;
            const net = amount - fee;
            document.getElementById('feeResult').innerText = `-$${fee.toFixed(2)}`;
            document.getElementById('netResult').innerText = `$${net.toFixed(2)}`;
        } else {
            preview.classList.add('hidden');
        }
    }
</script>
</html>