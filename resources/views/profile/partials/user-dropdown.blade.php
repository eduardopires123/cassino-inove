@php
    $Infos = App\Helpers\Core::getSetting();
@endphp

<div id="userMenuDropdown" aria-labelledby="userMenuButton" role="menu" tabindex="0" class="rVvdQ hidden">
    <div class="jG4B9" role="none">
        <section class="dSNk4" role="none">
            <header class="_1PmSH" role="none">
                <section class="V14En" role="none">
                    <div class="FYOz3" role="none">
                        <div class="_6aib6" role="none">
                            <img class="jk2P9" draggable="false" src="{{ asset('img/coin.png') }}" role="none" />
                            <span class="FPnY2" role="none">{{ Auth::user()->wallet->coin ?? 0 }}</span>
                        </div>
                        <div class="ALUPs" role="none">
                            <span class="inove-icon inove-icon--fill avatar-edit-btn" id="avatar-edit-btn">
                                <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.8 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"
                                        fill="currentColor"
                                    ></path>
                                </svg>
                            </span>
                        </div>
                        <img class="xHW6R" draggable="false" src="{{ asset($userImage) }}" role="none" />
                    </div>
                </section>
                <section class="DcHOq" role="none">
                    <div class="FXxbO" role="none">
                        <span class="FzpBR" role="none" style="text-transform: uppercase;">{{ explode(' ', $userName)[0] }}
                            <span class="nuxt-icon nuxt-icon--fill dqrpj">
                                <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.8 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"
                                        fill="currentColor"
                                    ></path>
                                </svg>
                            </span>
                        </span>
                    </div>
                    <div class="hHvHR" role="none">
                        <div class="Q-Rrc" role="none">
                            <img draggable="false" src="{{ asset($ranking['image']) }}" role="none" />
                        </div>
                        <div class="dlFd1" role="none">
                            <div class="iR3dB" role="none">
                                @if(isset($ranking) && isset($ranking['name']) && isset($ranking['level']))
                                <strong class="J22Y9" role="none">{{ $ranking['name'] }} </strong>
                                <span class="PBgwU" role="none">Nível {{ $ranking['level'] }}</span>
                                @endif
                            </div>
                            <a href="{{ route('vip.levels') }}" class="YZX2Q" role="none">
                                <span class="inove-icon inove-icon--fill inove-icon--stroke">
                                    <svg fill="#ffdf1b" height="1em" stroke="#ffdf1b" viewBox="0 0 140.599 140.599" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <g fill="#ffdf1b" stroke-width="0"></g>
                                        <g fill="#ffdf1b" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g fill="#ffdf1b">
                                            <g>
                                                <path
                                                    d="M132.861,56.559c-4.27,0-7.742,3.473-7.742,7.741c0,1.893,0.685,3.626,1.815,4.973l-15.464,15.463 c-2.754,2.754-4.557,1.857-4.027-2l0.062-0.445c0.528-3.857-1.39-4.876-4.286-2.273l-0.531,0.479 c-2.898,2.603-5.828,1.609-6.544-2.219l-5.604-29.964c-0.717-3.828-2.129-3.886-3.156-0.129l-7.023,25.689 c-1.025,3.757-2.295,3.677-2.834-0.181L71.93,33.674c3.488-0.751,6.111-3.856,6.111-7.566c0-4.268-3.473-7.741-7.741-7.741 c-4.269,0-7.742,3.473-7.742,7.741c0,3.709,2.625,6.815,6.112,7.566l-5.592,40.019c-0.539,3.857-1.809,3.938-2.835,0.181 l-7.023-25.69c-1.027-3.757-2.44-3.699-3.156,0.129l-5.605,29.964c-0.716,3.828-3.645,4.82-6.543,2.219l-0.533-0.479 c-2.897-2.604-4.816-1.586-4.287,2.272l0.061,0.445c0.529,3.858-1.274,4.753-4.028,2L13.667,69.273 c1.132-1.347,1.816-3.08,1.816-4.973c0-4.269-3.473-7.741-7.741-7.741C3.473,56.559,0,60.032,0,64.3 c0,4.269,3.473,7.742,7.742,7.742c0.478,0,0.942-0.05,1.396-0.132l10.037,33.949c1.104,3.734,3.534,9.637,7.161,11.055 c8.059,3.153,24.72,5.318,43.964,5.318c19.245,0,35.905-2.165,43.965-5.318c3.626-1.418,6.058-7.32,7.161-11.055l10.037-33.949 c0.453,0.083,0.918,0.132,1.396,0.132c4.268,0,7.739-3.473,7.739-7.742C140.6,60.032,137.127,56.559,132.861,56.559z M11.103,66.708c-0.685,0.954-1.761,1.605-2.994,1.714c-0.121,0.011-0.243,0.019-0.367,0.019c-2.284,0-4.142-1.857-4.142-4.142 c0-2.284,1.858-4.141,4.142-4.141c2.283,0,4.141,1.857,4.141,4.141C11.883,65.2,11.592,66.031,11.103,66.708z M66.159,26.109 c0-2.283,1.858-4.141,4.142-4.141c2.283,0,4.143,1.857,4.143,4.141c0,1.892-1.276,3.488-3.014,3.981 c-0.359,0.102-0.737,0.16-1.129,0.16s-0.769-0.058-1.128-0.16C67.436,29.596,66.159,28,66.159,26.109z M70.301,115.405 l-15.36-15.361l15.36-15.36l15.359,15.359L70.301,115.405z M132.861,68.442c-0.125,0-0.248-0.008-0.369-0.019 c-1.231-0.109-2.309-0.76-2.993-1.714c-0.488-0.68-0.779-1.51-0.779-2.409c0-2.284,1.856-4.141,4.142-4.141 c2.282,0,4.142,1.857,4.142,4.141C137.001,66.583,135.143,68.442,132.861,68.442z M60.036,100.046l10.27-10.27l10.269,10.27 l-10.269,10.27L60.036,100.046z"
                                                    fill="#ffdf1b"
                                                ></path>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                                <span class="wCOdk" role="none">{{ __('header.view_levels') }}</span>
                            </a>
                            <div class="uX5LM" role="none">
                                <div class="T2tDD" role="none">
                                    <div class="j0ZKE" role="none" style="width: {{ $progress }}%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </header>
            <main class="IL7WO" role="none">
                <div class="x5vpJ" role="none">{{ __('header.att') }} {{ now()->format('d/m/Y, H:i:s') }}</div>
                <nav class="lUBqG BZsnC" role="none" id="clubVipMenu" style="display: none;">
                    <a href="{{ route('lucky.boxes') }}" class="BntJr" role="none">
                        <span class="nuxt-icon nuxt-icon--fill Q-Rrc">
                            <svg height="1em" viewBox="0 0 576 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M547.6 103.8L490.3 13.1C485.2 5 476.1 0 466.4 0H109.6C99.9 0 90.8 5 85.7 13.1L28.3 103.8c-29.6 46.8-3.4 111.9 51.9 119.4c4 .5 8.1 .8 12.1 .8c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.2 0 49.3-11.4 65.2-29c16 17.6 39.1 29 65.2 29c4.1 0 8.1-.3 12.1-.8c55.5-7.4 81.8-72.5 52.1-119.4zM499.7 254.9l-.1 0c-5.3 .7-10.7 1.1-16.2 1.1c-12.4 0-24.3-1.9-35.4-5.3V384H128V250.6c-11.2 3.5-23.2 5.4-35.6 5.4c-5.5 0-11-.4-16.3-1.1l-.1 0c-4.1-.6-8.1-1.3-12-2.3V384v64c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V384 252.6c-4 1-8 1.8-12.3 2.3z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                        <span role="none">{{ __('header.store') }}</span>
                    </a>
                </nav>
                <footer class="TG-NI BZsnC" role="none" id="toggleClubVip">
                    <span class="nuxt-icon nuxt-icon--fill" id="toggleIcon">
                        <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                         <path d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z" fill="currentColor"></path>
                        </svg>
                    </span>
                    <span>{{ __('header.view_more_about') }}</span>
                </footer>
            </main>
        </section>
        <a class="XeHqK" href="{{ route('user.wallet') }}">
            <span class="inove-icon inove-icon--fill OzfKA" active="false" aria-hidden="true">
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M48 127.1L448 128C448.4 128 448.9 128 449.3 128C460.5 128.3 470.9 131.6 480 136.9V136.6C499.1 147.6 512 168.3 512 192V416C512 451.3 483.3 480 448 480H64C28.65 480 0 451.3 0 416V80C0 106.5 21.49 128 48 128L48 127.1zM416 336C433.7 336 448 321.7 448 304C448 286.3 433.7 272 416 272C398.3 272 384 286.3 384 304C384 321.7 398.3 336 416 336z"
                        fill="currentColor"
                    ></path>
                    <path d="M0 80C0 53.49 21.49 32 48 32H432C458.5 32 480 53.49 480 80V136.6C470.6 131.1 459.7 128 448 128L48 128C21.49 128 0 106.5 0 80V80z" fill="currentColor" opacity="0.4"></path>
                </svg>
            </span>
            {{ __('header.wallet') }}
        </a>
        @if ($Infos->enable_sports === 1 && isset($Infos->sports_api_provider) && $Infos->sports_api_provider === 'betby')
        <a class="XeHqK" href="{{ url('/sports/bets') }}" id="headlessui-menu-item-nsiNM9WAguS_43" role="menuitem" tabindex="-1" data-headlessui-state="">
        @else
        <a class="XeHqK" href="javascript: void(0);" onclick="LinkMobile('/bet-history');" id="headlessui-menu-item-nsiNM9WAguS_43" role="menuitem" tabindex="-1" data-headlessui-state="">
        @endif
            <span class="inove-icon inove-icon--fill OzfKA" active="false" aria-hidden="true">
                <svg viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg">
                    <path d="M448 128C465.7 128 480 142.3 480 160V352C480 369.7 465.7 384 448 384H128C110.3 384 96 369.7 96 352V160C96 142.3 110.3 128 128 128H448zM448 160H128V352H448V160z" fill="currentColor"></path>
                    <path
                        d="M128 160H448V352H128V160zM512 64C547.3 64 576 92.65 576 128V208C549.5 208 528 229.5 528 256C528 282.5 549.5 304 576 304V384C576 419.3 547.3 448 512 448H64C28.65 448 0 419.3 0 384V304C26.51 304 48 282.5 48 256C48 229.5 26.51 208 0 208V128C0 92.65 28.65 64 64 64H512zM96 352C96 369.7 110.3 384 128 384H448C465.7 384 480 369.7 480 352V160C480 142.3 465.7 128 448 128H128C110.3 128 96 142.3 96 160V352z"
                        fill="currentColor"
                        opacity="0.4"
                    ></path>
                </svg>
            </span>
            {{ __('header.betts') }}
        </a>
        <a class="XeHqK" href="{{ route('user.refers') }}" id="headlessui-menu-item-nsiNM9WAguS_43" role="menuitem" tabindex="-1" data-headlessui-state="">
            <span class="nuxt-icon nuxt-icon--fill OzfKA" active="false" aria-hidden="true">
                <svg viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg">
                    <path d="M444.4 310.4l-72.12 68.07c-3.49 3.291-8.607 4.191-13.01 2.299C354.9 378.9 352 374.6 352 369.8v-36.14H224v36.14c0 4.795-2.857 9.135-7.262 11.03c-4.406 1.895-9.523 .9941-13.01-2.297L131.6 310.4c-4.805-4.535-4.805-12.94 0-17.47L203.7 224.9C207.2 221.6 212.3 220.7 216.7 222.6C221.1 224.5 224 228.8 224 233.6v35.99h128V233.6c0-4.793 2.857-9.135 7.262-11.03c4.406-1.895 9.523-.9941 13.01 2.299l72.12 68.07C449.2 297.5 449.2 305.9 444.4 310.4z" fill="currentColor"></path>
                    <path d="M96 128c35.38 0 64-28.62 64-64S131.4 0 96 0S32 28.62 32 64S60.63 128 96 128zM128 160H64C28.65 160 0 188.7 0 224v96c0 17.67 14.33 32 31.1 32L32 480c0 17.67 14.33 32 32 32h64c17.67 0 32-14.33 32-32v-96.39l-50.36-47.53C100.1 327.9 96 316.2 96 304.1c0-12.16 4.971-23.83 13.64-32.01l72.13-68.08c1.65-1.555 3.773-2.311 5.611-3.578C177.1 176.8 155 160 128 160zM480 128c35.38 0 64-28.62 64-64s-28.62-64-64-64s-64 28.62-64 64S444.6 128 480 128zM512 160h-64c-26.1 0-49.98 16.77-59.38 40.42c1.842 1.271 3.969 2.027 5.623 3.588l72.12 68.06C475 280.2 480 291.9 480 304.1c.002 12.16-4.969 23.83-13.64 32.01L416 383.6V480c0 17.67 14.33 32 32 32h64c17.67 0 32-14.33 32-32v-128c17.67 0 32-14.33 32-32V224C576 188.7 547.3 160 512 160z" fill="currentColor" opacity="0.4"></path>
                </svg>
            </span>
            {{ __('menu.refer_earn') }}
        </a>
        <a class="XeHqK" href="{{ route('user.account') }}" class="dropdown-item">
            <span class="inove-icon inove-icon--fill OzfKA" active="false" aria-hidden="true">
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M256 272c39.77 0 72-32.24 72-72S295.8 128 256 128C216.2 128 184 160.2 184 200S216.2 272 256 272zM288 320H224c-47.54 0-87.54 29.88-103.7 71.71C155.1 426.5 203.1 448 256 448s100.9-21.53 135.7-56.29C375.5 349.9 335.5 320 288 320z"
                        fill="currentColor"
                    ></path>
                    <path
                        d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 128c39.77 0 72 32.24 72 72S295.8 272 256 272c-39.76 0-72-32.24-72-72S216.2 128 256 128zM256 448c-52.93 0-100.9-21.53-135.7-56.29C136.5 349.9 176.5 320 224 320h64c47.54 0 87.54 29.88 103.7 71.71C356.9 426.5 308.9 448 256 448z"
                        fill="currentColor"
                        opacity="0.4"
                    ></path>
                </svg>
            </span>
            {{ __('header.my_account') }}
        </a>
        <a class="XeHqK" href="{{ route('user.security') }}" class="dropdown-item">
            <span class="inove-icon inove-icon--fill OzfKA" active="false" aria-hidden="true">
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M160 176C160 78.8 238.8 0 336 0C433.2 0 512 78.8 512 176C512 273.2 433.2 352 336 352C317.3 352 299.2 349.1 282.3 343.7L168.3 229.7C162.9 212.8 160 194.7 160 176V176zM376 96C353.9 96 336 113.9 336 136C336 158.1 353.9 176 376 176C398.1 176 416 158.1 416 136C416 113.9 398.1 96 376 96z"
                        fill="currentColor"
                    ></path>
                    <path
                        d="M168.3 229.7L282.3 343.7C282.3 343.7 282.3 343.7 282.3 343.7L248.1 376.1C244.5 381.5 238.4 384 232 384H192V424C192 437.3 181.3 448 168 448H128V488C128 501.3 117.3 512 104 512H24C10.75 512 0 501.3 0 488V408C0 401.6 2.529 395.5 7.029 391L168.3 229.7C168.3 229.7 168.3 229.7 168.3 229.7V229.7z"
                        fill="currentColor"
                        opacity="0.4"
                    ></path>
                </svg>
            </span>
            {{ __('header.change_password') }}
        </a>
        <a class="XeHqK" href="{{route('lucky.boxes')}}" data-auth-link="true">
            <span class="inove-icon inove-icon--fill OzfKA" active="false" aria-hidden="true">
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M280.1 44.45C296.3 16.91 325.9 0 357.8 0H360C408.6 0 448 39.4 448 88C448 136.6 408.6 176 360 176H288V256H224V176H152C103.4 176 64 136.6 64 88C64 39.4 103.4 0 152 0H154.2C186.1 0 215.7 16.91 231.9 44.45L256 85.46L280.1 44.45zM190.5 68.78C182.9 55.91 169.1 48 154.2 48H152C129.9 48 112 65.91 112 88C112 110.1 129.9 128 152 128H225.3L190.5 68.78zM286.7 128H360C382.1 128 400 110.1 400 88C400 65.91 382.1 48 360 48H357.8C342.9 48 329.1 55.91 321.5 68.78L286.7 128zM224 512V288H288V512H224z"
                        fill="currentColor"
                    ></path>
                    <path
                        d="M152 176H224V256H32C14.33 256 0 241.7 0 224V160C0 142.3 14.33 128 32 128H73.6C88.16 156.5 117.8 176 152 176zM480 256H288V176H360C394.2 176 423.8 156.5 438.4 128H480C497.7 128 512 142.3 512 160V224C512 241.7 497.7 256 480 256zM32 288H224V512H80C53.49 512 32 490.5 32 464V288zM288 512V288H480V464C480 490.5 458.5 512 432 512H288z"
                        fill="currentColor"
                        opacity="0.6"
                    ></path>
                </svg>
            </span>
            {{ __('header.lucky') }}
        </a>
        <!-- <a href="{{ route('tickets.index') }}" class="XeHqK">
            <span class="inove-icon inove-icon--fill OzfKA" active="false" aria-hidden="true">
            <svg height="200px" width="200px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 58 58" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path style="fill:#bdbebe;" d="M33.224,10.494H13.776C6.168,10.494,0,16.661,0,24.27v11.345c0,7.608,6.392,13.879,14,13.879h0 v7.446c0,0.503,0.384,0.755,0.74,0.4l1.521-1.521c4.116-4.116,9.699-6.325,15.52-6.325h1.443C40.832,49.494,47,43.223,47,35.615 V24.27C47,16.661,40.832,10.494,33.224,10.494z"></path> <g> <path style="fill:#7e8080;" d="M44.224,0.494H24.776c-6.371,0-11.717,4.332-13.292,10.206c0.747-0.125,1.509-0.206,2.292-0.206 h19.448C40.832,10.494,47,16.661,47,24.27v11.345c0,1.259-0.183,2.476-0.5,3.639C52.957,38.061,58,32.37,58,25.615V14.27 C58,6.661,51.832,0.494,44.224,0.494z"></path> </g> <circle style="fill:#212425;" cx="12" cy="30.494" r="3"></circle> <circle style="fill:#212425;" cx="24" cy="30.494" r="3"></circle> <circle style="fill:#212425;" cx="36" cy="30.494" r="3"></circle> </g> </g></svg>
            </span>
            {{ __('messages.live_support') }}
        </a> -->
        <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
            @csrf
        </form>
        <button class="XeHqK wMNL2 logout-button" id="headlessui-menu-item-nsiNM9WAguS_50" role="menuitem" tabindex="-1" data-headlessui-state="">
            <span class="inove-icon inove-icon--fill OzfKA" active="false" aria-hidden="true">
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M504.8 273.5l-144.1 136c-6.975 6.578-17.2 8.375-26 4.594c-8.803-3.797-14.51-12.47-14.51-22.05l-.0918-71.1l-128-.001c-17.69 0-32.02-14.33-32.02-32v-64c0-17.67 14.34-32 32.02-32l128 .001l.0918-72c0-9.578 5.707-18.25 14.51-22.05c8.803-3.781 19.03-1.984 26 4.594l144.1 136C514.4 247.6 514.4 264.4 504.8 273.5z"
                        fill="currentColor"
                    ></path>
                    <path
                        d="M96 480h64C177.7 480 192 465.7 192 448S177.7 416 160 416H96c-17.67 0-32-14.33-32-32V128c0-17.67 14.33-32 32-32h64C177.7 96 192 81.67 192 64S177.7 32 160 32H96C42.98 32 0 74.98 0 128v256C0 437 42.98 480 96 480z"
                        fill="currentColor"
                        opacity="0.4"
                    ></path>
                </svg>
            </span>
            {{ __('header.logout') }}
        </button>
    </div>
</div>

<style>
.BntJr {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    text-decoration: none;
    color: inherit;
    transition: background-color 0.2s ease;
}

.BntJr:hover {
    background-color: rgba(0, 0, 0, 0.05);
    border-radius: 4px;
}

.lUBqG {
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 10px 0;
}

.TG-NI {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.TG-NI:hover {
    background-color: rgba(0, 0, 0, 0.05);
    border-radius: 4px;
}

.Q-Rrc {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
