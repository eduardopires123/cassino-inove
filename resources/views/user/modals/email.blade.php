<div class="_8XokL" data-v-owner="388" style="--8446db72: 450px;">
    <div class="X-T4C qRFSC">
        <button class="pOB1m hover:opacity-80 transition-opacity">
            <span class="nuxt-icon nuxt-icon--fill">
                <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                        fill="currentColor"
                    ></path>
                </svg>
            </span>
        </button>
        <div class="jOkUz">
            <div class="zJPno rounded-lg shadow-md" data-name="email" data-type="pendency">
                <div class="wJKi8 p-6">
                    <!----><!---->
                    <div class="kb3uu flex flex-col items-center gap-4">
                        <img alt="Email icon" aria-hidden="true" class="d8Kr- w-16 h-16 mb-2" src="{{ asset('img/email.svg') }}">
                        <h1 class="LlJu- text-xl font-semibold">Valide seu e-mail</h1>
                        <p class="M02Nh max-w-56 text-center">Insira o <strong>código de 5 dígitos</strong> enviado para seu e-mail:</p>
                        <span class="RvkWs font-medium mb-4">{{ auth()->user()->email }}</span>
                        <div class="w-11/12 md:w-10/12 mx-auto flex items-center justify-center gap-3 md:gap-4 mb-4">
                            <input autocomplete="off" class="s80BM w-12 h-14 text-center text-xl font-bold rounded-md border-2 focus:outline-none focus:ring-2" name="code" required="" type="tel" maxlength="1" />
                            <input autocomplete="off" class="s80BM w-12 h-14 text-center text-xl font-bold rounded-md border-2 focus:outline-none focus:ring-2" name="code" required="" type="tel" maxlength="1" />
                            <input autocomplete="off" class="s80BM w-12 h-14 text-center text-xl font-bold rounded-md border-2 focus:outline-none focus:ring-2" name="code" required="" type="tel" maxlength="1" />
                            <input autocomplete="off" class="s80BM w-12 h-14 text-center text-xl font-bold rounded-md border-2 focus:outline-none focus:ring-2" name="code" required="" type="tel" maxlength="1" />
                            <input autocomplete="off" class="s80BM w-12 h-14 text-center text-xl font-bold rounded-md border-2 focus:outline-none focus:ring-2" name="code" required="" type="tel" maxlength="1" />
                        </div>
                        <p class="_47CJe text-sm">Não recebeu o e-mail?</p>
                        <button class="gwv7B text-sm font-medium hover:underline transition-all" disabled="">REENVIAR CÓDIGO em 60s</button>
                        <button class="HbPca text-sm font-medium hover:underline transition-all">Trocar e-mail</button>
                        <div class="zYrLw mt-4 w-full">
                            <button class="rueTo btn-primary w-full py-3 rounded-lg font-medium flex items-center justify-center gap-2 transition-all hover:opacity-90" disabled="">
                                CONFIRMAR CÓDIGO
                                <span class="nuxt-icon nuxt-icon--fill">
                                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"
                                            fill="currentColor"
                                        ></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <!---->
            </div>
        </div>
    </div>
</div>
<style>
    .X-T4C.qRFSC{
       max-width: 450px;
       box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
       border-radius: 12px;
       overflow: hidden;
    }
    
    .btn-primary{
        color: var(--text-btn-primary);
    }
    
    .s80BM {
       transition: all 0.2s ease;
    }
    
    .s80BM:focus {
       transform: scale(1.05);
    }
    
    .gwv7B:not([disabled]), 
    .HbPca:not([disabled]) {
       cursor: pointer;
    }
    
    .rueTo:not([disabled]) {
       cursor: pointer;
       transform: translateY(0);
       transition: transform 0.2s ease;
    }
    
    .rueTo:not([disabled]):hover {
       transform: translateY(-2px);
    }
    
    .rueTo:not([disabled]):active {
       transform: translateY(0);
    }
</style>