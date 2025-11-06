<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    @vite(['resources/css/style.css', 'resources/js/index.js'])
    
    <title>
        {{ $page->title }}
    </title>
    @yield('extracss')
  </head>
  <body
    x-data="{ page: '', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark bg-gray-900': darkMode === true}"
  >

    @if ($page->modals != null)
      @foreach ($page->modals as $modal)
        {{ $modal->render() }}
      @endforeach
    @endif

    <!-- ===== Preloader Start ===== -->
    @include('layout.preloader')
    <!-- ===== Preloader End ===== -->
    
    @if ($page->topbar != null)
    {{ $page->topbar->render() }}
    @endif

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
      <!-- ===== Sidebar Start ===== -->
      @if ($page->sidebar != null)
      {{ $page->sidebar->render() }}
      @endif
      <!-- ===== Sidebar End ===== -->

      <!-- ===== Content Area Start ===== -->
      <div
        class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto"
      >
        <!-- Small Device Overlay Start -->
        @include('layout.overlay')
        <!-- Small Device Overlay End -->

        <!-- ===== Header Start ===== -->
        @if ($page->header != null)
        {{ $page->header->render() }}
        @endif
        <!-- ===== Header End ===== -->

        <!-- ===== Main Content Start ===== -->
        <main class="p-4">
          @if ($page->content != null)
          {{ $page->content->render() }}
          @endif
        </main>
        <!-- ===== Main Content End ===== -->
      </div>
      <!-- ===== Content Area End ===== -->
    </div>
    <!-- ===== Page Wrapper End ===== -->
  </body>
</html>


