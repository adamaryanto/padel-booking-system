<section>
    <header class="mb-4">
        <h2 class="text-lg font-black text-white italic uppercase tracking-tighter font-heading mb-1">
            {{ __('Delete Account') }}
        </h2>
        <p class="text-sm text-secondary">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
        </p>
    </header>

    <button type="button" class="bg-red-500/10 text-red-500 border border-red-500/20 font-black uppercase tracking-wider text-xs py-[0.75rem] px-[2rem] rounded-xl hover:bg-red-500 hover:text-white transition-all duration-300 flex items-center shadow-lg group" data-toggle="modal" data-target="#confirmUserDeletionModal">
        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        {{ __('Delete Account') }}
    </button>

    <!-- Deletion Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" role="dialog" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark-card/95 border border-white/5 rounded-[2rem] shadow-2xl backdrop-blur-xl">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header border-white/5 p-6">
                        <h5 class="modal-title font-black text-white italic uppercase tracking-tighter" id="confirmUserDeletionModalLabel">
                            <span class="text-red-500">DELETE</span> ACCOUNT
                        </h5>
                        <button type="button" class="close text-white/50 hover:text-white transition outline-none focus:outline-none" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-6">
                        <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-relaxed mb-6">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}
                        </p>

                        <div class="form-group mb-0">
                            <label for="password" class="sr-only">{{ __('Password') }}</label>
                            <input id="password" name="password" type="password" 
                                class="bg-dark border border-white/5 rounded-xl p-4 text-white w-full focus:border-red-500 focus:ring-1 focus:ring-red-500 font-bold transition-all placeholder:text-gray-700 outline-none @error('password', 'userDeletion') border-red-500 @enderror" 
                                placeholder="CURRENT PASSWORD">
                            @error('password', 'userDeletion')
                                <div class="text-red-500 text-[9px] font-black uppercase tracking-[0.2em] mt-3 ml-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer border-white/5 p-6 pt-0 flex space-x-3">
                        <button type="button" class="flex-1 bg-white/5 text-white/60 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:text-white hover:bg-white/10 transition" data-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="flex-1 bg-red-500 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-600 transition shadow-lg shadow-red-500/20">
                            {{ __('Confirm Deletion') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>