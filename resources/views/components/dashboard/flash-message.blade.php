@if (session('status'))
    <div class="mb-5 rounded-[7px] border border-[#bce8ce] bg-[#e9f9ef] px-5 py-4 text-[14px] font-bold text-[#007a3d] shadow-[0_8px_18px_rgba(38,74,112,0.04)]">
        {{ session('status') }}
    </div>
@endif
