@props(['width' => 'xl'])

<div x-show="open" style="display:none" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div
    x-show="open"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
  </div>
  
  <!-- accepts: sm:max-w-xl sm:max-w-2xl sm:max-w-3xl sm:max-w-4xl sm:max-w-5xl -->
  <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
    <div class="flex min-h-full items-start justify-center p-4 text-center sm:items-center sm:p-0">
      <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        class="relative transform rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-{{ $width }}">
        
        {{ $slot }}
      </div>
    </div>
  </div>
</div>