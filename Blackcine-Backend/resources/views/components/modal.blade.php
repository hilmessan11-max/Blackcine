@props(['id', 'title', 'size' => 'md'])

@php
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-7xl',
    ];
@endphp

<!-- Modal Overlay -->
<div id="{{ $id }}" class="modal-overlay fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <!-- Modal Content -->
    <div class="modal-content bg-white {{ $sizeClasses[$size] }} w-full rounded-xl shadow-2xl">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="material-symbols-outlined mr-2 text-red-600">{{ $icon ?? 'info' }}</span>
                {{ $title }}
            </h3>
            <button onclick="closeModal('{{ $id }}')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            {{ $slot }}
        </div>
        
        <!-- Modal Footer -->
        @isset($footer)
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }
    
    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
        }
    });
    
    // Fermer en cliquant sur l'overlay
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });
</script>

