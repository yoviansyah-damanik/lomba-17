<div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4 sm:p-6 space-y-6"
    @if ($criteria->isNotEmpty()) x-data @endif>
    <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $registration->displayName() }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $registration->npp }} &middot;
            {{ $registration->school_type }}</p>
    </div>

    @if ($criteria->isEmpty())
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Tidak ada kriteria yang dapat dinilai saat ini. Pastikan Anda memiliki kriteria yang ditugaskan dan jadwal lombanya sedang berlangsung.') }}
        </p>
    @else
        <form wire:submit="review" class="space-y-4" autocomplete="off">
            @foreach ($criteria as $criterion)
                <div class="ring-1 ring-gray-200 dark:ring-gray-700 rounded-xl p-4 space-y-3">
                    <div>
                        <x-input-label :value="$criterion->name" />
                        @if ($criterion->description)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $criterion->description }}</p>
                        @endif
                    </div>

                    <div>
                        <x-input-label :for="'score-' . $criterion->id" :value="__('Nilai (0-31)')" />
                        <div class="mt-1 flex items-center gap-3">
                            <button type="button" wire:click="decrementScore('{{ $criterion->id }}')"
                                class="shrink-0 h-11 w-11 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-xl font-bold hover:bg-gray-50 dark:hover:bg-gray-700 active:bg-gray-100 dark:active:bg-gray-600">
                                &minus;
                            </button>
                            <input wire:model="scores.{{ $criterion->id }}" id="score-{{ $criterion->id }}"
                                type="number" min="0" max="31" autocomplete="off"
                                class="w-full text-center text-xl font-bold border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-red-500 focus:ring-red-500 rounded-lg shadow-sm scroll-mt-36">
                            <button type="button" wire:click="incrementScore('{{ $criterion->id }}')"
                                class="shrink-0 h-11 w-11 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-xl font-bold hover:bg-gray-50 dark:hover:bg-gray-700 active:bg-gray-100 dark:active:bg-gray-600">
                                +
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('scores.' . $criterion->id)" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <x-input-label :for="'notes-' . $criterion->id" :value="__('Catatan')" />
                            <button type="button" wire:click="$set('notes.{{ $criterion->id }}', '')"
                                class="text-xs font-medium text-gray-400 hover:text-red-600 dark:hover:text-red-400">
                                {{ __('Kosongkan') }}
                            </button>
                        </div>
                        <div class="relative mt-1" x-data="{
                            listening: false,
                            supported: 'webkitSpeechRecognition' in window || 'SpeechRecognition' in window,
                            rec: null,
                            baseText: '',
                            finalText: '',
                            applyText(text) {
                                this.$refs.notesInput.value = text;
                                // set .value tidak memicu event 'input' bawaan browser,
                                // jadi wire:model tidak akan tersinkron tanpa dispatch manual ini.
                                this.$refs.notesInput.dispatchEvent(new Event('input'));
                            },
                            stopListening() {
                                this.listening = false;
                                if (this.rec) {
                                    // detach handler dulu supaya event onresult/onend yang telat
                                    // (setelah abort) tidak lagi mengubah state.
                                    this.rec.onresult = null;
                                    this.rec.onend = null;
                                    this.rec.onerror = null;
                                    // abort() langsung memutus koneksi, beda dengan stop() yang
                                    // masih menunggu hasil akhir diproses (itu sebabnya mic
                                    // terasa tidak langsung mati saat diklik).
                                    this.rec.abort();
                                    this.rec = null;
                                }
                            },
                            toggle() {
                                if (!this.supported) return;
                                if (this.listening) {
                                    this.stopListening();
                                    return;
                                }
                                const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                                this.rec = new SR();
                                this.rec.lang = 'id-ID';
                                this.rec.continuous = true;
                                this.rec.interimResults = true;
                                this.baseText = this.$refs.notesInput.value.trim();
                                this.finalText = '';
                                this.rec.onresult = (e) => {
                                    // Dibangun ulang dari index 0 tiap event (bukan diakumulasi dari
                                    // e.resultIndex) karena Chrome kadang mengulang hasil yang sudah
                                    // final di event berikutnya — kalau diakumulasi, kata yang sama
                                    // akan tertulis dobel.
                                    let final = '';
                                    let interim = '';
                                    for (let i = 0; i < e.results.length; i++) {
                                        const transcript = e.results[i][0].transcript;
                                        if (e.results[i].isFinal) {
                                            final += transcript + ' ';
                                        } else {
                                            interim += transcript;
                                        }
                                    }
                                    this.finalText = final;
                                    this.applyText([this.baseText, (this.finalText + interim).trim()].filter(Boolean).join(' '));
                                };
                                this.rec.onend = () => this.stopListening();
                                this.rec.onerror = () => this.stopListening();
                                this.rec.start();
                                this.listening = true;
                            },
                        }">
                            <textarea wire:model="notes.{{ $criterion->id }}" x-ref="notesInput" id="notes-{{ $criterion->id }}" rows="2" autocomplete="off"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 rounded-lg shadow-sm block w-full pr-10"></textarea>
                            <button type="button" x-show="supported" @click="toggle()"
                                :class="listening ? 'text-red-600 animate-pulse' :
                                    'text-gray-400 hover:text-red-600 dark:hover:text-red-400'"
                                class="absolute right-2 bottom-2"
                                :aria-label="listening ? '{{ __('Berhenti merekam') }}' : '{{ __('Isi catatan dengan suara') }}'">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 15a3 3 0 003-3V6a3 3 0 10-6 0v6a3 3 0 003 3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 11a7 7 0 01-14 0M12 18v3" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('notes.' . $criterion->id)" class="mt-2" />
                    </div>
                </div>
            @endforeach

            <div class="h-20 sm:h-16" aria-hidden="true"></div>

            <div
                class="fixed inset-x-0 bottom-16 sm:bottom-0 z-40 bg-white/95 dark:bg-gray-800/95 backdrop-blur border-t border-gray-200 dark:border-gray-700 px-4 py-3 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto flex justify-end">
                    <x-primary-button type="submit" class="w-full sm:w-auto">{{ __('Simpan') }}</x-primary-button>
                </div>
            </div>
        </form>

        <x-simple-modal :show="$showConfirm" show-property="showConfirm">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Konfirmasi Nilai') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $registration->displayName() }}</p>

            <div class="mt-4 space-y-3">
                @foreach ($criteria as $criterion)
                    <div
                        class="flex items-start justify-between gap-4 border-t border-gray-100 dark:border-gray-700 pt-3 first:border-t-0 first:pt-0">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $criterion->name }}</p>
                            @if (filled($notes[$criterion->id] ?? null))
                                <p class="text-xs italic text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $notes[$criterion->id] }}</p>
                            @endif
                        </div>
                        <p class="shrink-0 text-lg font-bold text-red-600 dark:text-red-400">
                            {{ $scores[$criterion->id] ?? '-' }}</p>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <x-secondary-button type="button" wire:click="cancelReview">{{ __('Kembali') }}</x-secondary-button>
                <x-primary-button type="button" wire:click="save">{{ __('Konfirmasi & Simpan') }}</x-primary-button>
            </div>
        </x-simple-modal>
    @endif
</div>
