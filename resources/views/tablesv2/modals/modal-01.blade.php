<div class="delete-modal hidden">
    <div class="flex items-center justify-center absolute w-screen h-screen bg-gray-800/80 z-100000">
        <div class="absolute flex flex-col text-center justify-center bg-gray-200 dark:bg-gray-900 rounded-3xl p-8 text-black dark:text-white opacity-100 font-outfit w-6/7 lg:w-5/14">
            @include('layout.action-icons.caution')
            <div class="py-4 text-sm">
                <h3 class="font-semibold text-2xl mb-2">{{ $caution_message }}</h3>
                <p class="mb-2">{{ $action }}</p>

                <div class="flex flex-col my-4">
                    @for ($i = 0; $i < count($columns); $i++)
                    <div class="flex flex-col gap-1 mb-3">
                        <span class="modal-col{{$i}}">{{ $columns[$i] }}</span>
                        <p class="text-gray-500 dark:text-gray-400 modal-row{{$i}}">{{ $rows[$i] }}</p>
                    </div>
                    @endfor
                </div>

                <p class="italic">{{ $last_warning_message }}</p>
            </div>

            <div class="flex items-center justify-center gap-4 text-sm">
                @if (isset($is_form) && $is_form)
                    <form method="POST" class="data-form hidden">
                        @method('DELETE')
                        @csrf
                        <input name="{{ $data_input_name }}" type="text" class="data-input">
                    </form>
                @endif
                <button data-id="" class="text-white modal-confirm-button px-4 py-3 bg-error-500 rounded-xl" href="#">{{ $confirm_button }}</button>

                <a class="modal_cancel_button px-4 py-3 text-gray-700 dark:text-gray-300" href="#">{{ $cancel_button }}</a>
            </div>
            
        </div>
    </div>
</div>
