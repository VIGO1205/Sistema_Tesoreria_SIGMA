@extends('base.administrativo.blank')

@section('titulo')
  Dashboard de Matrículas y Pagos
@endsection

@section('contenido')
<div class="p-8 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-950 dark:to-gray-850 min-h-screen font-sans antialiased">
    {{-- Fondo degradado suave con tonos azules/índigo para una sensación educativa/financiera --}}

    <div class="mb-10 p-8 bg-white dark:bg-gray-800 rounded-3xl shadow-2xl transform transition-all duration-500 hover:shadow-indigo-400/40 hover:-translate-y-1 border border-blue-100 dark:border-gray-700">
        {{-- Sombra más pronunciada y un sutil "halo" de color índigo al pasar el ratón. Borde más suave. --}}
        <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-3 leading-tight">
            👋 ¡Bienvenida, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-400 dark:to-indigo-500">{{ Auth::user()->name }}</span>!
        </h2>
        {{-- Degradado de texto más enfocado en azules e índigos. --}}
        <p class="text-xl text-gray-700 dark:text-gray-300">
            Tu centro de control para la gestión de matrículas y el seguimiento de pagos.
        </p>
        {{-- Texto descriptivo ajustado a la temática. --}}
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
        {{-- Tarjeta 1: Matrículas activas --}}
        <div class="relative bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-7 shadow-2xl text-white overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-indigo-500/40 cursor-pointer group">
            {{-- Degradado azul-índigo fuerte, overflow-hidden para los íconos de fondo, y un grupo para efectos hover --}}
            <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-20 text-white group-hover:rotate-12 transition-transform duration-300">
                {{-- Icono de birrete/graduación para matrículas --}}
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9l11 6 11-6-11-6zm0 13.91L1 20.91l11 6 11-6-11-6z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold opacity-90 mb-2 z-10 relative">Matrículas activas</h3>
            <p class="text-4xl font-extrabold z-10 relative">{{$totalMatriculas}}</p>
            <div class="text-sm mt-3 opacity-80 z-10 relative">Estudiantes actualmente inscritos y con matrícula válida.</div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-white opacity-20 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
            {{-- Línea inferior que se expande al pasar el ratón, simbolizando progreso --}}
        </div>

        {{-- Tarjeta 2: Pagos del mes --}}
        <div class="relative bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-7 shadow-2xl text-white overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-emerald-500/40 cursor-pointer group">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-20 text-white group-hover:rotate-12 transition-transform duration-300">
                {{-- Icono de dinero/moneda para pagos --}}
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold opacity-90 mb-2 z-10 relative">Pagos del mes</h3>
            <p class="text-4xl font-extrabold z-10 relative">S/. {{ number_format($totalPagosMes, 2, ',', '.') }}</p>
            <div class="text-sm mt-3 opacity-80 z-10 relative">Total de ingresos recibidos durante el mes actual.</div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-white opacity-20 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </div>

        {{-- Tarjeta 3: Alumnos registrados --}}
        <div class="relative bg-gradient-to-br from-purple-600 to-pink-700 rounded-3xl p-7 shadow-2xl text-white overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-pink-500/40 cursor-pointer group">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-20 text-white group-hover:rotate-12 transition-transform duration-300">
                {{-- Icono de grupo de personas para alumnos --}}
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.94 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold opacity-90 mb-2 z-10 relative">Alumnos registrados</h3>
            <p class="text-4xl font-extrabold z-10 relative">{{$totalAlumnos}}</p>
            <div class="text-sm mt-3 opacity-80 z-10 relative">Cantidad total de alumnos en la base de datos del sistema.</div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-white opacity-20 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </div>

        {{-- Tarjeta 4: Deudas pendientes --}}
        <div class="relative bg-gradient-to-br from-red-600 to-orange-700 rounded-3xl p-7 shadow-2xl text-white overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-orange-500/40 cursor-pointer group">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-20 text-white group-hover:rotate-12 transition-transform duration-300">
                {{-- Icono de alerta/exclamación para deudas --}}
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold opacity-90 mb-2 z-10 relative">Deudas pendientes</h3>
            <p class="text-4xl font-extrabold z-10 relative">S/. {{ number_format($totalDeudasPendientes, 2, ',', '.') }}</p>
            <div class="text-sm mt-3 opacity-80 z-10 relative">Monto acumulado por matrículas o cuotas aún no cubiertas.</div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-white opacity-20 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </div>
    </div>

</div>
 
 
@endsection


