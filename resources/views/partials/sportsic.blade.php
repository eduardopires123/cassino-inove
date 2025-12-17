<div id="divMenuHighlight" class="lhS3o" style="margin-bottom:30px;">
    <div class="SM-j1">
        <div class="h9HDs">
            <h2 data-v-debf714a="" class="title flex" style="text-transform:uppercase;">{!! $homeSections->getSectionTitle('custom_title_sports_icons', '🏆 ' . __('menu.sportshome')) !!}</h2>
        </div>
        <div class="relative group flex items-center">
                <span class="inove-icon inove-icon--fill nQro9 cursor-pointer">
                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" fill="currentColor"></path>
                    </svg>
                </span>
                        <span class="inove-icon inove-icon--fill nQro9 cursor-pointer">
                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z" fill="currentColor"></path>
                    </svg>
                </span>
         </div>
    </div>
    <div class="-JVa3 Vulse EEtS9" style="--620ba053: 0px; --063993a6: 8px; --8ec19218: auto; --595ba9a7: 1;">
        <div class="rpneC uyA-x H3vO2">
            @if(isset($cachedData['leagueIcons_cache']) && is_iterable($cachedData['leagueIcons_cache']))
                @foreach($cachedData['leagueIcons_cache'] as $index => $icon)
                    <div class="peBY3 pt-4" style="order: {{ $index + 1 }};">
                        <button class="cursor-pointer kTNoj" href="javascript: void(0);" 
                            @if($icon->link)
                                @php
                                    // Verificar se é uma função JavaScript (contém parênteses)
                                    $isJsFunction = strpos($icon->link, '(') !== false && strpos($icon->link, ')') !== false;
                                @endphp
                                @if($isJsFunction)
                                    onclick="{{ $icon->link }}"
                                @else
                                    onclick="window.location.href='{{ $icon->link }}'"
                                @endif
                            @endif>
                            @if($icon->hot == 1)
                                <div class="_4Q1WF pl-1.5 py-0.5 pr-3 justify-between text-white text-[8px] font-bold uppercase">
                                    <img
                                        alt="hot"
                                        data-nuxt-img=""
                                        src="{{ asset('img/hot.png') }}"
                                    />
                                    <p>hot</p>
                                </div>
                            @elseif($icon->hot == 2)
                                <div class="_4Q1WF pl-1.5 py-0.5 pr-3 justify-between text-white text-[8px] font-bold uppercase" style="background: linear-gradient(rgb(248, 185, 27), rgb(255, 230, 167)); color: rgb(10, 25, 42);">
                                    <img
                                        alt="hot" style="width: 10px; height: 10px;"
                                        data-nuxt-img=""
                                        src="{{ asset('img/new.png') }}"
                                    />
                                    <p style="color: #000;">New</p>
                                </div>
                            @endif
                            <div class="C68xp">
                            <span class="nuxt-icon EB38O" aria-hidden="true">
                                {!! $icon->svg !!}
                            </span>
                            </div>
                            <div class="SR23B">
                                @php
                                    // Divide o nome em partes se contiver <small> ou dividir em duas partes
                                    if (strpos($icon->name, '<small>') !== false) {
                                        // Extract content from <small> tag and convert it to a <p> tag
                                        $smallContent = preg_match('/<small>(.*?)<\/small>/', $icon->name, $matches) ? $matches[1] : '';

                                        // Get the rest of the content outside <small> tag
                                        $otherContent = preg_replace('/<small>.*?<\/small>/', '', $icon->name);

                                        echo "<p>{$otherContent}</p><p>{$smallContent}</p>";
                                    } else {
                                        // If there's no <small> tag, put all content in a single <p>
                                        echo "<p>{$icon->name}</p>";
                                    }
                                @endphp
                            </div>
                        </button>
                    </div>
                @endforeach
            @endif
        </div>
        <!----><!---->
    </div>
    @php
        // Verificar qual provedor de API de sports está ativo
        $sportsApiProvider = App\Models\Settings::getSportsApiProvider();
        $isBetbyActive = $sportsApiProvider === 'betby';
        $isDigitainActive = $sportsApiProvider === 'digitain' || $sportsApiProvider === null;
    @endphp

    <div class="-JVa3 Vulse EEtS9" style="--620ba053: 0px; --063993a6: 8px; --8ec19218: auto; --595ba9a7: 1;">
        <div class="rpneC uyA-x H3vO2">
            <div class="peBY3 pt-4" style="order: 1;">
                @if ($isBetbyActive)
                    <button class="cursor-pointer _9FKX9" href="javascript: void(0);" onclick="window.location.href='/sports/live'" style="background-image: url('{{ asset('img/bg-button.png') }}');">
                @else
                    <button class="cursor-pointer _9FKX9" href="javascript: void(0);" onclick="LinkMobile('/Live/page');" style="background-image: url('{{ asset('img/bg-button.png') }}');">
                @endif
                    <span class="nuxt-icon EB38O" aria-hidden="true">
                        <svg width="28" height="20" viewBox="0 0 28 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14 13.8048C11.7934 13.8048 9.99799 12.0065 9.99799 9.79623C9.99799 7.586 11.7934 5.78766 14 5.78766C16.2066 5.78766 18.002 7.586 18.002 9.79623C18.002 12.0065 16.2066 13.8048 14 13.8048ZM14 7.79194C12.9154 7.79194 11.999 8.70991 11.999 9.79623C11.999 10.8825 12.9154 11.8005 14 11.8005C15.0845 11.8005 16.001 10.8825 16.001 9.79623C16.001 8.70991 15.0845 7.79194 14 7.79194Z"
                                fill="var(--primary-color)"
                            ></path>
                            <path
                                opacity="0.4"
                                d="M21.7824 18.5938C21.5263 18.5938 21.2706 18.4961 21.075 18.3002C20.6843 17.9088 20.6843 17.2745 21.075 16.8831C24.9755 12.9758 24.9755 6.6177 21.075 2.71035C20.6843 2.31901 20.6843 1.68466 21.075 1.29332C21.4657 0.901983 22.099 0.901983 22.4897 1.29332C27.1701 5.98184 27.1701 13.6117 22.4897 18.3002C22.2941 18.4956 22.038 18.5938 21.7824 18.5938Z"
                                fill="var(--primary-color)"
                            ></path>
                            <path
                                opacity="0.7"
                                d="M18.952 15.7587C18.6958 15.7587 18.4402 15.661 18.2446 15.4651C17.8539 15.0737 17.8539 14.4389 18.2446 14.0481C19.3787 12.9126 20.003 11.4024 20.003 9.79647C20.003 8.19054 19.3787 6.68031 18.2446 5.54488C17.8539 5.15405 17.8539 4.51919 18.2446 4.12785C18.6353 3.73652 19.2686 3.73652 19.6593 4.12785C21.1716 5.64159 22.004 7.65489 22.004 9.79647C22.004 11.938 21.1716 13.9514 19.6593 15.4651C19.4642 15.661 19.2081 15.7587 18.952 15.7587Z"
                                fill="var(--primary-color)"
                            ></path>
                            <path
                                opacity="0.7"
                                d="M9.04799 15.7587C8.79187 15.7587 8.53624 15.661 8.34064 15.4651C6.82839 13.9514 5.99597 11.938 5.99597 9.79647C5.99597 7.65489 6.82839 5.64159 8.34064 4.12785C8.73134 3.73652 9.36465 3.73652 9.75535 4.12785C10.146 4.51919 10.146 5.15405 9.75535 5.54488C8.62128 6.68031 7.99697 8.19054 7.99697 9.79647C7.99697 11.4024 8.62128 12.9126 9.75535 14.0481C10.146 14.4389 10.146 15.0737 9.75535 15.4651C9.55975 15.661 9.30362 15.7587 9.04799 15.7587Z"
                                fill="var(--primary-color)"
                            ></path>
                            <path
                                opacity="0.4"
                                d="M6.2176 18.5938C5.96148 18.5938 5.70585 18.4961 5.51025 18.3002C0.829916 13.6117 0.829916 5.98184 5.51025 1.29332C5.90095 0.901983 6.53426 0.901983 6.92496 1.29332C7.31565 1.68466 7.31565 2.31901 6.92496 2.71035C3.02451 6.6177 3.02451 12.9758 6.92496 16.8831C7.31565 17.2745 7.31565 17.9088 6.92496 18.3002C6.72986 18.4956 6.47373 18.5938 6.2176 18.5938Z"
                                fill="var(--primary-color)"
                            ></path>
                        </svg>
                    </span>
                    <div class="SR23B">
                        <p>Esportes</p>
                        <p>Ao vivo</p>
                    </div>
                </button>
            </div>
            <div class="peBY3 pt-4" style="order: 2;">
                @if ($isBetbyActive)
                    <button class="cursor-pointer _9FKX9" style="background-image: url('{{ asset('img/bg-button.png') }}');" href="javascript: void(0);" onclick="window.location.href='/sports'">
                @else
                    <button class="cursor-pointer _9FKX9" style="background-image: url('{{ asset('img/bg-button.png') }}');" href="javascript: void(0);" onclick="LinkMobile('/SportEvents/5566');">
                @endif
                    <span class="nuxt-icon EB38O" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_2389_667)">
                                <path d="M23.8182 14.781C22.686 14.6028 21.8172 13.6203 21.8172 12.4389L21.8172 11.6023C21.8172 10.4207 22.686 9.43817 23.8182 9.26001L24 9.26001L24 14.781L23.8182 14.781Z" fill="var(--primary-color)"></path>
                                <path
                                    opacity="0.6"
                                    d="M13.0902 8.23187C14.8806 8.56348 16.2411 10.1356 16.2411 12.0205C16.2411 13.9054 14.8806 15.4775 13.0902 15.8091L13.0902 21.873C13.0902 21.9254 13.084 21.9763 13.0732 22.0254L20.4844 22.0254C22.4229 22.0254 24 20.4483 24 18.5098L24 16.2114C22.0043 16.1129 20.4109 14.4584 20.4109 12.4389L20.4109 11.6023C20.4109 9.58264 22.0043 7.9281 24 7.82977L24 5.53125C24 3.59271 22.4229 2.01562 20.4844 2.01562L13.0732 2.01562C13.084 2.0647 13.0902 2.1156 13.0902 2.16797L13.0902 8.23187Z"
                                    fill="var(--primary-color)"
                                ></path>
                                <path
                                    opacity="0.6"
                                    d="M11.684 15.8091C9.89355 15.4775 8.5329 13.9054 8.5329 12.0205C8.5329 10.1356 9.89355 8.56348 11.684 8.23187L11.684 2.16797C11.684 2.1156 11.69 2.0647 11.701 2.01562L3.51562 2.01562C1.57709 2.01562 -6.89367e-08 3.59271 -1.53673e-07 5.53125L-2.54144e-07 7.82977C1.99567 7.9281 3.58905 9.58264 3.58905 11.6023L3.58905 12.4389C3.58905 14.4586 1.99567 16.1129 -6.20518e-07 16.2114L-7.20982e-07 18.5098C-8.05718e-07 20.4483 1.57709 22.0254 3.51562 22.0254L11.701 22.0254C11.69 21.9763 11.684 21.9254 11.684 21.873L11.684 15.8091Z"
                                    fill="var(--primary-color)"
                                ></path>
                                <path d="M2.1828 12.4389L2.1828 11.6023C2.1828 10.3583 1.21967 9.33563 0 9.23932L-2.43139e-07 14.8017C1.21967 14.7054 2.1828 13.6827 2.1828 12.4389Z" fill="var(--primary-color)"></path>
                                <path d="M13.0902 14.365C14.0982 14.062 14.8348 13.1259 14.8348 12.0205C14.8348 10.9151 14.0982 9.97906 13.0902 9.67621L13.0902 14.365Z" fill="var(--primary-color)"></path>
                                <path d="M11.684 9.67621C10.676 9.97906 9.93915 10.9151 9.93915 12.0205C9.93915 13.1259 10.676 14.062 11.684 14.365L11.684 9.67621Z" fill="var(--primary-color)"></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_2389_667">
                                    <rect width="24" height="24" fill="white" transform="translate(24) rotate(90)"></rect>
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                    <div class="SR23B">
                        <p>Esportes</p>
                        <p>Próximos Eventos</p>
                    </div>
                </button>
            </div>
            <div class="peBY3 pt-4" style="order: 3;">
                @if ($isBetbyActive)
                    <button class="cursor-pointer _9FKX9" style="background-image: url('{{ asset('img/bg-button.png') }}');" onclick="window.location.href='/sports/soccer-1'">
                @else
                    <button class="cursor-pointer _9FKX9" style="background-image: url('{{ asset('img/bg-button.png') }}');" onclick="window.location.href='/sports'">
                @endif
                    <span class="nuxt-icon EB38O" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_2389_680)">
                                <path
                                    d="M3.95863 8.08462L0.785556 10.5212C0.775941 10.3481 0.771133 10.175 0.771133 10C0.767297 7.8932 1.48728 5.84901 2.81056 4.20962L3.95863 8.08462ZM2.20383 14.9442C3.45898 16.9213 5.41786 18.3482 7.68459 18.9365L6.27113 14.9442H2.20383ZM12.3202 18.9365C14.5869 18.3482 16.5458 16.9213 17.8009 14.9442H13.7336L12.3202 18.9365ZM17.1894 4.20962L16.0413 8.08462L19.2144 10.5212C19.224 10.3481 19.2288 10.175 19.2288 10C19.2327 7.8932 18.5127 5.84901 17.1894 4.20962ZM13.4856 1.45C11.2505 0.5423 8.74948 0.5423 6.5144 1.45L9.99998 3.84616L13.4856 1.45ZM12.3769 13.0769L13.8461 8.79808L9.99998 6.15385L6.15383 8.79808L7.62306 13.0769H12.3769Z"
                                    fill="var(--primary-color)"
                                ></path>
                                <path
                                    opacity="0.6"
                                    d="M10 0C8.02219 0 6.08879 0.58649 4.4443 1.6853C2.79981 2.78412 1.51809 4.3459 0.761209 6.17317C0.00433284 8.00043 -0.193701 10.0111 0.192152 11.9509C0.578004 13.8907 1.53041 15.6725 2.92894 17.0711C4.32746 18.4696 6.10929 19.422 8.0491 19.8079C9.98891 20.1937 11.9996 19.9957 13.8268 19.2388C15.6541 18.4819 17.2159 17.2002 18.3147 15.5557C19.4135 13.9112 20 11.9778 20 10C19.9972 7.34869 18.9427 4.80678 17.068 2.93202C15.1932 1.05727 12.6513 0.00279983 10 0ZM17.3577 14.175H14.125L13.2346 12.9481L14.449 9.41154L15.8914 8.94135L18.4144 10.8769C18.295 12.0377 17.9351 13.1609 17.3577 14.175ZM1.58847 10.8769L4.10673 8.94231L5.54904 9.4125L6.76347 12.949L5.875 14.175H2.64231C2.06424 13.1611 1.70369 12.0379 1.58366 10.8769H1.58847ZM2.55 6.00769L3.07981 7.79615L1.60673 8.92019C1.73727 7.90031 2.05407 6.91309 2.54135 6.00769H2.55ZM8.17308 12.3077L7.07116 9.10096L10 7.0875L12.9289 9.10096L11.8269 12.3077H8.17308ZM16.9298 7.79615L17.4596 6.00769C17.9469 6.91309 18.2637 7.90031 18.3942 8.92019L16.9298 7.79615ZM16.3337 4.39423L15.4192 7.47885L13.9702 7.94904L10.7692 5.74904V4.25096L13.5663 2.32788C14.6198 2.82062 15.5613 3.52384 16.3327 4.39423H16.3337ZM11.7375 1.71827L10 2.9125L8.2625 1.71827C9.40842 1.47841 10.5916 1.47841 11.7375 1.71827ZM6.43366 2.32788L9.23077 4.25096V5.74904L6.03077 7.94904L4.58174 7.47885L3.66731 4.39423C4.43871 3.52384 5.38016 2.82062 6.43366 2.32788ZM3.76443 15.7135H5.72308L6.4125 17.6625C5.41137 17.1914 4.51185 16.5293 3.76443 15.7135ZM8.26923 18.2817L7.12693 15.0683L8.01443 13.8462H11.9856L12.8731 15.0683L11.7356 18.2817C10.591 18.5216 9.40905 18.5216 8.26443 18.2817H8.26923ZM13.5923 17.6625L14.2817 15.7135H16.2404C15.4915 16.5298 14.5904 17.1919 13.5875 17.6625H13.5923Z"
                                    fill="var(--primary-color)"
                                ></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_2389_680">
                                    <rect width="20" height="20" fill="white"></rect>
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                    <div class="SR23B">
                        <p>Esportes</p>
                        <p>Futebol</p>
                    </div>
                </button>
            </div>
            <div class="peBY3 pt-4" style="order: 4;">
                @if ($isBetbyActive)
                    <button class="cursor-pointer _9FKX9" style="background-image: url('{{ asset('img/bg-button.png') }}');" href="javascript: void(0);" onclick="window.location.href='/sports/basketball-2'">
                @else
                    <button class="cursor-pointer _9FKX9" style="background-image: url('{{ asset('img/bg-button.png') }}');" href="javascript: void(0);" onclick="LinkMobile('/SportEvents/998');">
                @endif
                    <span class="nuxt-icon EB38O" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_2389_713)">
                                <path d="M8.82167 10L6.46 12.3617L6.4575 12.3642C4.73824 10.8707 2.54626 10.0333 0.269167 10H0C-0.00071212 7.63643 0.8397 5.34974 2.37083 3.54917L8.82167 10Z" fill="var(--primary-color)"></path>
                                <path d="M10 0.269167C10.0335 2.54622 10.8709 4.73813 12.3642 6.4575L10 8.82167L3.54917 2.37083C5.34974 0.8397 7.63643 -0.00071212 10 0V0.269167Z" fill="var(--primary-color)"></path>
                                <path d="M13.5425 7.63583C15.2618 9.12916 17.4538 9.96656 19.7308 10H20C20.0007 12.3636 19.1603 14.6503 17.6292 16.4508L11.1783 10L13.5425 7.63583Z" fill="var(--primary-color)"></path>
                                <path d="M10 19.7067C9.93665 17.4427 9.10264 15.2682 7.63583 13.5425L10 11.1783L16.4508 17.6292C14.6503 19.1603 12.3636 20.0007 10 20V19.7067Z" fill="var(--primary-color)"></path>
                                <path
                                    opacity="0.6"
                                    d="M13.5433 5.27832C12.3113 3.85645 11.6436 2.03207 11.6667 0.150818C13.4319 0.447394 15.0846 1.21429 16.4508 2.37082L13.5433 5.27832ZM17.6292 3.54915L14.7217 6.45665C16.1553 7.6685 17.9719 8.33337 19.8492 8.33332C19.5526 6.56806 18.7857 4.91537 17.6292 3.54915ZM6.455 14.7233L3.54917 17.6292C4.91538 18.7857 6.56807 19.5526 8.33333 19.8492C8.33513 17.9718 7.66937 16.155 6.455 14.7233ZM0.150833 11.6667C0.44741 13.4319 1.21431 15.0846 2.37083 16.4508L5.27833 13.5433C3.84759 12.3264 2.02909 11.6608 0.150833 11.6667Z"
                                    fill="var(--primary-color)"
                                ></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_2389_713">
                                    <rect width="20" height="20" fill="white"></rect>
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                    <div class="SR23B">
                        <p>Esportes</p>
                        <p>Basquete</p>
                    </div>
                </button>
            </div>
        </div>
        <!----><!---->
    </div>
</div>

