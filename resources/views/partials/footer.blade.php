@php
    $footerData = \App\Http\Controllers\PartialsController::getFooterData();
    extract($footerData);
@endphp
<footer>
    <div class="_-29CJ">
        <div class="f9O1J">
            @auth
                <div class="JG6dg">{{ __('footer.hello') }}, <strong>{{ auth()->user()->name ?? __('footer.visitor') }}</strong></div>
                <span>{{ __('footer.time_logged') }}: <strong id="timeLoggedCounter">{{ $initialTimeLogged ?? '00h 00m 00s' }}</strong></span>
                <span>{{ __('footer.last_login') }}: <strong>{{ $formattedLastLogin ?? 'N/A' }}</strong></span>
            @else
                <div class="JG6dg">{{ __('footer.hello') }}, <strong>{{ __('footer.visitor') }}</strong></div>
                <!---->
                <span>{{ __('footer.login_to_access') }}</span>
            @endauth
        </div>
    </div>
    <div data-v-d6b2e344="" id="divPageFooter" class="footer" style="--62def037: 50px;">
        <div data-v-ec39842a="" class="info-section-grid">
            <div data-v-ec39842a="" class="xl:flex flex-col">
                <div data-v-ec39842a="" class="l6oz0 h-10 min-w-full lg:min-w-[120px] justify-center lg:justify-start max-w-[120px] !mr-0">
                    <a aria-current="page" href="/" class="router-link-active router-link-exact-active bwSJI" aria-label="{{ $siteInfo->name ?? config('app.name') }}">
                        <img alt="{{ $siteInfo->name ?? config('app.name') }}" class="Ueilo" src="{{ completeImageUrl($siteInfo->logo ?? 'img/logo-inove.png') }}" />
                        <img alt="{{ $siteInfo->name ?? config('app.name') }}" class="j2x6J" src="{{ completeImageUrl($siteInfo->logo ?? 'img/logo-inove.png') }}" />
                    </a>
                </div>
                <div data-v-ec39842a="" class="flex mt-4 md:mt-6">
                    @if($showSocialLinks)
                        <div data-v-ec39842a="" class="social">
                            <!---->
                            <div data-v-ec39842a="" class="social">
                                <div class="flex items-center flex-col">
                                    <div class="lWeyP">
                                        @if($showInstagram && $instagramUrl)
                                            <a data-v-1319e6ac="" aria-label="Ver nosso perfil no instagram" class="social_link" href="{{ $instagramUrl }}" rel="noopener" target="_blank">
                        <span class="inove-icon inove-icon--fill" aria-hidden="true">
                            <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                                            </a>
                                        @endif
                                        @if($showFacebook && $facebookUrl)
                                            <a data-v-1319e6ac="" aria-label="Ver nosso perfil no Facebook" class="social_link" href="{{ $facebookUrl }}" rel="noopener" target="_blank">
                        <span class="inove-icon inove-icon--fill" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" height="1em" width="1em">
                                <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" fill="currentColor"/>
                            </svg>
                        </span>
                                            </a>
                                        @endif
                                        @if($showWhatsapp && $whatsappUrl)
                                            <a data-v-1319e6ac="" aria-label="Acesse Nosso WhatsApp" class="social_link" href="{{ $whatsappUrl }}" rel="noopener" target="_blank">
                        <span class="inove-icon inove-icon--fill" aria-hidden="true">
                            <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                                            </a>
                                        @endif
                                        @if($showTelegram && $telegramUrl)
                                            <a data-v-1319e6ac="" aria-label="Acesse Nosso Telegram" class="social_link" href="{{ $telegramUrl }}" rel="noopener" target="_blank">
                        <span class="inove-icon inove-icon--fill" aria-hidden="true">
                            <svg height="1em" viewBox="0 0 496 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M248,8C111.033,8,0,119.033,0,256S111.033,504,248,504,496,392.967,496,256,384.967,8,248,8ZM362.952,176.66c-3.732,39.215-19.881,134.378-28.1,178.3-3.476,18.584-10.322,24.816-16.948,25.425-14.4,1.326-25.338-9.517-39.287-18.661-21.827-14.308-34.158-23.215-55.346-37.177-24.485-16.135-8.612-25,5.342-39.5,3.652-3.793,67.107-61.51,68.335-66.746.153-.655.3-3.1-1.154-4.384s-3.59-.849-5.135-.5q-3.283.746-104.608,69.142-14.845,10.194-26.894,9.934c-8.855-.191-25.888-5.006-38.551-9.123-15.531-5.048-27.875-7.717-26.8-16.291q.84-6.7,18.45-13.7,108.446-47.248,144.628-62.3c68.872-28.647,83.183-33.623,92.511-33.789,2.052-.034,6.639.474,9.61,2.885a10.452,10.452,0,0,1,3.53,6.716A43.765,43.765,0,0,1,362.952,176.66Z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div data-v-ec39842a="" class="flex flex-col">
                <b data-v-ec39842a="" class="title">{{ __('footer.bet') }}</b>
                <div data-v-ec39842a="" class="flex flex-col menu-items">
                    <a data-v-ec39842a="" href="{{ \App\Models\Settings::isBetbyActive() ? route('sports.betby') : route('esportes') }}" class="">{{ __('footer.sports_betting') }}</a>
                    <a data-v-ec39842a="" href="javascript:void(0);" onclick="LinkMobile('/Live/page');" class="">{{ __('footer.live_sports') }}</a>
                    <a data-v-ec39842a="" href="{{ route ('cassino.slots') }}" class="">{{ __('footer.slot_games') }}</a>
                    <a data-v-ec39842a="" href="{{ route ('cassino.ao-vivo') }}" class="">{{ __('footer.live_games') }}</a>
                    <!---->
                </div>
            </div>
            <div data-v-ec39842a="" class="flex flex-col">
                <b data-v-ec39842a="" class="title">{{ __('footer.casino') }}</b>
                <div data-v-ec39842a="" class="flex flex-col menu-items">
                    <a data-v-ec39842a="" href="{{ route('cassino.todos-jogos') }}" class=""><span data-v-ec39842a="">{{ __('footer.all_games') }}</span></a>
                    <a data-v-ec39842a="" href="{{ route('cassino.slots') }}" rel="noopener noreferrer" target="_blank"><span data-v-ec39842a="">{{ __('footer.slots') }}</span></a>
                    <a data-v-ec39842a="" href="{{ route('cassino.ao-vivo') }}" class=""><span data-v-ec39842a="">{{ __('footer.live_casino') }}</span></a>
                    <a data-v-ec39842a="" href="{{ route('cassino.provedores') }}" rel="noopener noreferrer" target="_blank">{{ __('footer.providers') }}</a>
                    <!---->
                </div>
            </div>
            <div data-v-ec39842a="" class="flex flex-col">
                <b data-v-ec39842a="" class="title">{{ __('footer.rules') }}</b>
                <div data-v-ec39842a="" class="flex flex-col menu-items">
                    <a data-v-ec39842a="" href="{{ route('terms') }}" class="menuLinks">{{ __('footer.terms_conditions') }}</a>
                    <a data-v-ec39842a="" href="{{ route('responsible.gaming') }}" class="menuLinks">{{ __('footer.responsible_gaming') }}</a>
                    <a data-v-ec39842a="" href="{{ route('aml-policy') }}" class="menuLinks">{{ __('footer.kyc_policy') }}</a>
                    <a data-v-ec39842a="" href="{{ route('betting.terms') }}" class="menuLinks">{{ __('footer.betting_terms') }}</a>
                    <a data-v-ec39842a="" href="{{ route('privacy') }}" class="menuLinks">{{ __('footer.privacy_policy') }}</a>
                    <a data-v-ec39842a="" href="{{ route('lgpd') }}" class="menuLinks">{{ __('footer.lgpd') }}</a>
                </div>
            </div>
            <div data-v-ec39842a="" class="flex flex-col">
                <b data-v-ec39842a="" class="title">{{ __('footer.useful_links') }}</b>
                <div data-v-ec39842a="" class="flex flex-col items-start gap-3">
                    <a data-v-ec39842a="" href="{{ $contactButtonUrl }}" class="contactButton">
                        <span data-v-ec39842a="" class="inove-icon inove-icon--fill buttonIcon">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21.5416 21.7865C21.5753 21.9386 21.5747 22.0964 21.5397 22.2483C21.5048 22.4002 21.4365 22.5424 21.3397 22.6646C21.2429 22.7868 21.1202 22.8859 20.9803 22.9547C20.8405 23.0236 20.6871 23.0604 20.5312 23.0625C20.3704 23.0616 20.2119 23.0242 20.0677 22.9531L18.2239 22.0521C16.5009 22.6813 14.6141 22.6998 12.8791 22.1044C11.1442 21.509 9.66626 20.3359 8.69269 18.7812C9.99712 18.9504 11.3225 18.8431 12.5828 18.4664C13.8431 18.0898 15.01 17.4521 16.0077 16.5949C17.0053 15.7377 17.8115 14.6802 18.3737 13.491C18.9359 12.3019 19.2415 11.0077 19.2708 9.69271C19.2713 9.096 19.2103 8.50083 19.0885 7.91666C20.3356 8.52155 21.3961 9.45212 22.158 10.61C22.9198 11.7679 23.3547 13.1101 23.4166 14.4948C23.4579 15.5447 23.28 16.5917 22.8945 17.5691C22.5089 18.5465 21.924 19.4329 21.1771 20.1719L21.5416 21.7865Z"
                                    fill="currentColor"
                                    fill-opacity="0.5"
                                ></path>
                                <path
                                    d="M9.89583 1.5625C7.71384 1.53816 5.61124 2.3801 4.04908 3.90369C2.48692 5.42729 1.5927 7.50819 1.5625 9.69011C1.56176 10.7979 1.79279 11.8935 2.24074 12.9066C2.6887 13.9198 3.34366 14.828 4.16354 15.5729L3.82813 17.3635C3.80021 17.5139 3.80566 17.6685 3.84408 17.8166C3.8825 17.9646 3.95296 18.1023 4.05048 18.2201C4.148 18.3379 4.2702 18.4329 4.40845 18.4982C4.54669 18.5636 4.6976 18.5978 4.85052 18.5984C5.02346 18.5981 5.19362 18.555 5.34583 18.4729L7.31875 17.413C8.15173 17.6802 9.02105 17.8169 9.89583 17.8182C12.0779 17.8426 14.1806 17.0006 15.7428 15.4769C17.3049 13.9532 18.1991 11.8721 18.2292 9.69011C18.199 7.50819 17.3047 5.42729 15.7426 3.90369C14.1804 2.3801 12.0778 1.53816 9.89583 1.5625ZM6.77084 10.9375C6.56481 10.9375 6.36342 10.8764 6.19212 10.762C6.02081 10.6475 5.8873 10.4848 5.80846 10.2945C5.72962 10.1041 5.70899 9.89468 5.74918 9.69262C5.78938 9.49055 5.88859 9.30495 6.03427 9.15927C6.17995 9.01359 6.36555 8.91438 6.56762 8.87418C6.76968 8.83399 6.97912 8.85462 7.16946 8.93346C7.3598 9.0123 7.52249 9.14582 7.63695 9.31712C7.75141 9.48842 7.8125 9.68981 7.8125 9.89584C7.8125 10.1721 7.70275 10.4371 7.5074 10.6324C7.31205 10.8278 7.0471 10.9375 6.77084 10.9375ZM9.89583 10.9375C9.68981 10.9375 9.48842 10.8764 9.31712 10.762C9.14581 10.6475 9.0123 10.4848 8.93346 10.2945C8.85462 10.1041 8.83399 9.89468 8.87418 9.69262C8.91438 9.49055 9.01359 9.30495 9.15927 9.15927C9.30495 9.01359 9.49055 8.91438 9.69262 8.87418C9.89468 8.83399 10.1041 8.85462 10.2945 8.93346C10.4848 9.0123 10.6475 9.14582 10.7619 9.31712C10.8764 9.48842 10.9375 9.68981 10.9375 9.89584C10.9375 10.1721 10.8278 10.4371 10.6324 10.6324C10.4371 10.8278 10.1721 10.9375 9.89583 10.9375ZM13.0208 10.9375C12.8148 10.9375 12.6134 10.8764 12.4421 10.762C12.2708 10.6475 12.1373 10.4848 12.0585 10.2945C11.9796 10.1041 11.959 9.89468 11.9992 9.69262C12.0394 9.49055 12.1386 9.30495 12.2843 9.15927C12.4299 9.01359 12.6156 8.91438 12.8176 8.87418C13.0197 8.83399 13.2291 8.85462 13.4195 8.93346C13.6098 9.0123 13.7725 9.14582 13.8869 9.31712C14.0014 9.48842 14.0625 9.68981 14.0625 9.89584C14.0625 10.1721 13.9528 10.4371 13.7574 10.6324C13.5621 10.8278 13.2971 10.9375 13.0208 10.9375Z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                        {{ __('footer.contact_button') }}
                    </a>
                </div>
            </div>
            <div data-v-ec39842a="" class="flex flex-col">
                <b data-v-ec39842a="" class="title">{{ __('footer.payment') }}</b>
                <div data-v-ec39842a="" class="flex flex-col menu-items paymentItems">
                    <span data-v-ec39842a="" class="inove-icon h-6 w-full">
                        <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 952.8 338.7">
                            <path
                                d="M638.9,34.8l-11.3-11.3c-2.8-2.8-2.8-7.3,0-10.1h0l11.3-11.3c2.8-2.8,7.3-2.8,10.2,0l11.3,11.3c2.8,2.8,2.8,7.3,0,10.1h0l-11.3,11.3c-2.8,2.8-7.3,2.8-10.1,0h0M246.1,264.5c-12.3,0-24.1-4.8-32.8-13.5l-47.4-47.4c-3.5-3.3-9-3.3-12.4,0l-47.5,47.5c-8.7,8.7-20.5,13.6-32.8,13.6h-9.3l60,60c18.7,18.7,49.1,18.7,67.8,0l60.1-60.1h-5.8.1ZM73.3,97.1c12.3,0,24.1,4.9,32.8,13.6l47.5,47.5c3.4,3.4,9,3.4,12.4,0l47.3-47.3c8.7-8.7,20.5-13.6,32.8-13.6h5.7l-60.1-60.1c-18.7-18.7-49.1-18.7-67.8,0h0l-59.9,59.9s9.3,0,9.3,0ZM301.6,147l-36.3-36.3c-.8.3-1.7.5-2.6.5h-16.5c-8.6,0-16.8,3.4-22.9,9.5l-47.3,47.3c-8.9,8.9-23.3,8.9-32.1,0l-47.5-47.5c-6.1-6.1-14.3-9.5-22.9-9.5h-20.3c-.8,0-1.7-.2-2.4-.5l-36.6,36.5c-18.7,18.7-18.7,49.1,0,67.8l36.5,36.5c.8-.3,1.6-.5,2.4-.5h20.4c8.6,0,16.8-3.4,22.9-9.5l47.5-47.5c8.6-8.6,23.6-8.6,32.1,0l47.3,47.3c6.1,6.1,14.3,9.5,22.9,9.5h16.5c.9,0,1.8.2,2.6.5l36.3-36.3c18.7-18.7,18.7-49.1,0-67.8h0"
                                style="fill: #32bcad; stroke-width: 0px;"
                            ></path>
                            <path
                                d="M582.8,122v41.3c0,39-31.7,70.7-70.7,70.7h-81.1c-3.3,0-6-2.7-6-6s2.7-6,6-6h81.1c32.4,0,58.7-26.4,58.7-58.8v-41.3c0-32.3-26.3-58.6-58.5-58.7h-57.3c-32.4,0-58.7,26.3-58.7,58.7v194.3c0,3.3-2.7,6-6,6s-6-2.7-6-6V122c0-39,31.7-70.7,70.7-70.7h57.4c38.9.2,70.5,31.8,70.4,70.7h0ZM617.7,51.3h-24.9c-3.3,0-6,2.7-6,6s2.7,6,6,6h24.9c11.4,0,20.6,9.2,20.6,20.6v145.1c0,3.3,2.7,6,6,6s6-2.7,6-6V83.8c0-17.9-14.6-32.5-32.5-32.5h0ZM692,63.1h24.7c11.2,0,21.7,4.4,29.6,12.3l57.7,57.7c4.7,4.7,11,7.4,17.8,7.3,6.7,0,13-2.6,17.7-7.3l57.6-57.6c7.9-7.9,18.4-12.3,29.6-12.3h20.1c3.3,0,6-2.7,6-6s-2.7-6-6-6h-20.1c-14.4,0-27.9,5.6-38,15.7l-57.5,57.5c-5.1,5.1-13.5,5.1-18.6,0l-57.7-57.7c-10.2-10.2-23.7-15.7-38.1-15.7h-24.7c-3.3,0-6,2.7-6,6s2.7,6,6,6h0ZM946.7,221.5h-20.1c-11.2,0-21.7-4.4-29.6-12.3l-57.5-57.5c-9.8-9.8-25.7-9.8-35.5,0l-57.7,57.7c-7.9,7.9-18.4,12.3-29.6,12.3h-24.7c-3.3,0-6,2.7-6,6s2.7,6,6,6h24.7c14.4,0,27.9-5.6,38-15.7l57.7-57.7c5.1-5.1,13.5-5.1,18.6,0l57.5,57.5c10.2,10.2,23.7,15.7,38,15.7h20.1c3.3,0,6-2.7,6-6s-2.7-6-6-6h0ZM442.5,299.8c4.5,0,6.8,2.2,6.8,7.1s-2.8,7.2-8.8,7.2-4.7-.4-6.9-1.2v-11.8c2.9-.8,5.9-1.2,8.9-1.4M433.7,321.7v-6.9c2.3.7,4.7,1.1,7.1,1.1s4.8-.5,6.9-1.5c2.8-1.6,3.8-4.6,3.8-7.5s-.7-5.6-2.7-7.3c-1.8-1.3-3.9-1.9-6.1-1.8-3.1.2-6.2.7-9.3,1.5l-.2-1h-1.6v23.4h2.1ZM466.4,299.7c5.9,0,8.5,2.3,8.5,7.4s-2.6,7.1-8.5,7.1-8.6-2.3-8.6-7.3,2.6-7.1,8.6-7.1M474,314.1c2.2-1.5,3-4.1,3-7.2s-.8-5.6-3-7.2c-2-1.4-4.6-1.8-7.6-1.8s-5.7.4-7.7,1.8c-2.2,1.6-3.1,4-3.1,7.2s.9,5.7,3.1,7.2c1.9,1.4,4.6,1.8,7.7,1.8s5.6-.4,7.6-1.8M503.9,315.5l7.1-17.1h-2.2l-5.7,14.4h-.1l-6.5-14.4h-1.8l-6.4,14.4h-.1l-5.9-14.4h-2.2l7,17.1h1.8l6.5-14.2h.1l6.6,14.2h1.8ZM523.8,299.6c5.5,0,7.5,2.1,7.7,6h-15.4c.3-3.5,2.2-6,7.7-6M523.7,315.9c3,0,6-.4,9-1v-1.8c-2.9.6-5.9.9-8.8,1-6.7,0-7.8-3.1-7.9-6.6h17.5c0-3.3-.5-6.2-3-7.9-2.1-1.2-4.5-1.8-6.8-1.7-2.4-.1-4.7.5-6.7,1.7-2.3,1.7-3.2,4.4-3.2,7.3s.7,5.4,2.7,7.1c1.7,1.4,3.8,1.9,7.1,1.9M541.3,315.4v-14.4c3.8-1,5.4-1.4,8.1-1.4h.5v-1.9h-.2c-3.2,0-4.9.6-8.5,1.5l-.2-1h-1.6v17.1h2,0ZM561.5,299.6c5.5,0,7.5,2.1,7.7,6h-15.4c.3-3.5,2.2-6,7.7-6M561.4,315.9c3,0,6-.4,9-1v-1.8c-2.9.6-5.9.9-8.9,1-6.7,0-7.8-3.1-7.9-6.6h17.6c0-3.3-.5-6.2-3-7.9-2.1-1.2-4.5-1.8-6.8-1.7-2.4-.1-4.7.5-6.7,1.7-2.3,1.7-3.2,4.4-3.2,7.3s.7,5.4,2.7,7.1c1.7,1.4,3.8,1.9,7.1,1.9M593.2,312.7c-2.9.7-5.9,1.2-8.9,1.3-4.5,0-6.8-2.2-6.8-7.1s2.8-7.2,8.8-7.2,4.7.4,6.9,1.1v11.9h0ZM595.2,315.5v-24.4h-2v7.8c-2.3-.7-4.7-1-7.1-1.1-2.4,0-4.8.5-6.9,1.6-2.8,1.6-3.8,4.4-3.8,7.5s.7,5.6,2.7,7.2c1.7,1.3,3.9,1.9,6.1,1.8,3.1-.2,6.2-.7,9.3-1.5l.2,1h1.6,0ZM624.5,299.8c4.5,0,6.8,2.2,6.8,7.1s-2.8,7.2-8.8,7.2-4.7-.4-6.9-1.2v-11.8c2.9-.8,5.9-1.2,8.9-1.4M629.7,314.4c2.8-1.6,3.8-4.6,3.8-7.5s-.7-5.6-2.7-7.3c-1.8-1.3-3.9-1.9-6.1-1.8-3.1.2-6.1.7-9.1,1.4v-8.2h-2v24.3h1.4v-.8c2.6.8,5.2,1.3,7.8,1.3s4.7-.5,6.9-1.5M636.1,322c.8.1,1.6.2,2.5.2,3.5,0,5.4-1.2,7.2-4.6l9.6-19.2h-2.3l-7.3,14.8h0l-7.7-14.8h-2.3l9,17.1-.7,1.4c-1.4,2.8-2.9,3.5-5.4,3.5s-1.5,0-2.5-.2v1.9M683,305.7c4,0,6.3.3,6.3,3s-1.7,3-6.3,3h-6.6v-6h6.6M681.8,296c4.1,0,6.3.3,6.3,3s-1.8,3.1-6.3,3.1h-5.5v-6.1h5.4M692.1,313.8c1.7-1,2.6-2.8,2.5-4.7,0-3.3-1.9-4.8-4.9-5.5h0c2.7-1.2,3.7-2.9,3.7-5.3,0-1.9-.8-3.7-2.4-4.6-2.4-1.4-5.2-1.5-9.9-1.5h-9.9v23.3h10.2c5.4,0,8.3,0,10.8-1.6M714.8,311.6c-2.4.6-4.8.9-7.2,1-2.7,0-3.7-.7-3.7-2.3s1.2-2.3,4.7-2.3,4.2,0,6.2.3v3.3ZM719.7,315.5v-10.1c0-2.5-.4-4.5-2.3-5.9s-5-1.6-8-1.6-5,.1-7.4.3v3.5c2.2-.2,4.5-.3,6.3-.3,4.8,0,6.6.9,6.6,3.6v.3c-2-.1-4.4-.2-6.2-.2s-4.6,0-6.4.9c-.6.3-1.2.8-1.7,1.3-1.9,2.2-1.6,5.6.7,7.5,1.6.9,3.4,1.4,5.2,1.3,3.1-.1,6.3-.6,9.3-1.5v1h4,0ZM746.7,315.5v-10.5c0-2.4-.5-4.1-1.9-5.4-1.7-1.3-3.8-1.9-5.9-1.8-3.1.2-6.2.7-9.2,1.6l-.2-1.1h-3.9v17.1h4.8v-12.6c2.3-.6,4.6-1,7-1.1,2.8,0,4.4,1.1,4.4,4.2v9.5h4.9ZM760.2,315.9c2.8,0,5.6-.5,8.3-1.1v-3.5c-2.4.5-4.8.8-7.2.9-3.7,0-5.3-1.6-5.3-5.3s2.1-5.4,6.7-5.4,3.7.1,5.5.3v-3.7c-2.1-.2-4-.3-6-.3s-5.4.3-7.5,1.7c-2.6,1.7-3.5,4.6-3.5,7.3s.6,5.6,2.7,7.3c1.8,1.5,4.2,1.8,6.4,1.8M782.7,301.4c4.5,0,6.5,1.7,6.5,5.6s-1.9,5.3-6.5,5.3-6.5-1.7-6.5-5.6,2-5.3,6.5-5.3M790.9,314.1c2.3-1.6,3.2-4.2,3.2-7.3s-.8-5.7-3.2-7.2c-2.1-1.4-4.8-1.8-8.2-1.8s-6.2.4-8.2,1.8c-2.4,1.6-3.2,4.2-3.2,7.3s.8,5.7,3.2,7.2c2.1,1.4,4.9,1.8,8.2,1.8s6.1-.4,8.2-1.8M821.7,315.9c3.8-.1,7.5-.6,11.2-1.5v-4.1c-3.4.8-6.9,1.3-10.3,1.4-4.9,0-7.5-2.5-7.5-7.9s2.8-7.9,9.4-7.9,5.4.2,7.9.4v-4.2c-2.9-.2-5.5-.4-8.1-.4s-7,.4-9.8,2.2c-3.6,2.4-4.7,6.4-4.7,9.9s.8,7.4,3.6,9.7c2.3,1.9,5.5,2.4,8.3,2.4M847,300.9c4,0,5.6,1.2,5.7,4h-11.5c.3-2.6,1.8-4,5.8-4M846.3,315.9c3.5,0,6.9-.5,10.3-1.1v-3.4c-3,.5-6.1.8-9.1.9-4.8,0-6.2-1.7-6.3-4.3h16.3c0-3.5-.2-6.5-3-8.4-2.1-1.5-5-1.7-7.4-1.7s-5.4.3-7.5,1.8c-2.4,1.7-3.2,4.6-3.2,7.2s.8,5.6,2.8,7.2,4.5,1.9,7.3,1.9M883.5,315.5v-10.5c0-2.4-.5-4.1-1.9-5.4-1.7-1.3-3.8-1.9-5.9-1.8-3.1.2-6.2.7-9.2,1.6l-.2-1.1h-3.9v17.1h4.8v-12.6c2.3-.6,4.6-1,7-1.1,2.8,0,4.4,1.1,4.4,4.2v9.5h4.9ZM897.1,315.9c1.7,0,3.3-.3,5-.6v-3.4c-1.1.2-2.3.3-3.4.3s-2.6-.4-3.1-1.6c-.4-1-.5-2.2-.5-3.3v-5.5h6.8v-3.6h-6.8v-5.2h-4.2l-.5,5.2h-3.5v3.6h3.5v6.4c0,1.8.3,3.6,1.3,5.2,1.2,1.8,3.2,2.5,5.6,2.5M911.2,315.5v-12.6c2.2-.6,4.5-.9,6.7-.9h1.7v-4.1h-.6c-2.9.1-5.7.6-8.5,1.6l-.2-1.1h-3.9v17.1h4.8ZM937,311.6c-2.4.6-4.8.9-7.2,1-2.7,0-3.7-.7-3.7-2.3s1.2-2.3,4.7-2.3,4.2,0,6.2.3v3.3ZM941.8,315.5v-10.1c0-2.5-.4-4.5-2.3-5.9s-5-1.6-8-1.6-5,.1-7.4.3v3.5c2.2-.2,4.4-.3,6.3-.3,4.8,0,6.6.9,6.6,3.6v.3c-2-.1-4.4-.2-6.2-.2s-4.6,0-6.4.9c-1.8.8-3,2.6-2.9,4.6,0,1.6.6,3.2,1.9,4.1,1.6.9,3.4,1.4,5.2,1.3,3.1-.1,6.3-.6,9.3-1.5v1h3.9ZM947.9,315.5h4.8v-24.4h-4.8v24.4Z"
                                style="fill: #f9f9f9; stroke-width: 0px;"
                            ></path>
                        </svg>
                    </span>
                    </a>
                </div>
            </div>
        </div>

        <div data-v-ec39842a="" class="info-section-socials">
            <div data-v-ec39842a="" class="l6oz0 h-8 min-w-full lg:min-w-[120px] justify-center lg:justify-start max-w-[120px] !mr-0">
                <a aria-current="page" href="/" class="router-link-active router-link-exact-active bwSJI" aria-label="{{ $siteInfo->name ?? config('app.name') }}">
                    <img alt="{{ $siteInfo->name ?? config('app.name') }}" class="Ueilo" src="{{ completeImageUrl($siteInfo->logo ?? 'img/logo-inove.png') }}" />
                    <img alt="{{ $siteInfo->name ?? config('app.name') }}" class="j2x6J" src="{{ completeImageUrl($siteInfo->logo ?? 'img/logo-inove.png') }}" />
                </a>
            </div>
            <div data-v-ec39842a="" class="flex mt-4">
                <div data-v-ec39842a="" class="social">
                    <div class="flex items-center flex-col">
                        <div class="lWeyP">
                            @if($showInstagram && $instagramUrl)
                                <a data-v-1319e6ac="" aria-label="Ver nosso perfil no instagram" class="social_link" href="{{ $instagramUrl }}" rel="noopener" target="_blank">
                        <span class="inove-icon inove-icon--fill" aria-hidden="true">
                            <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" fill="currentColor"></path>
                            </svg>
                        </span>
                                </a>
                            @endif
                            @if($showFacebook && $facebookUrl)
                                <a data-v-1319e6ac="" aria-label="Ver nosso perfil no Facebook" class="social_link" href="{{ $facebookUrl }}" rel="noopener" target="_blank">
                        <span class="inove-icon inove-icon--fill" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" height="1em" width="1em">
                                <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" fill="currentColor"/>
                            </svg>
                        </span>
                                </a>
                            @endif
                            @if($showWhatsapp && $whatsappUrl)
                                <a data-v-1319e6ac="" aria-label="Acesse Nosso WhatsApp" class="social_link" href="{{ $whatsappUrl }}" rel="noopener" target="_blank">
                        <span class="inove-icon inove-icon--fill" aria-hidden="true">
                            <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                                </a>
                            @endif
                            @if($showTelegram && $telegramUrl)
                                <a data-v-1319e6ac="" aria-label="Acesse Nosso Telegram" class="social_link" href="{{ $telegramUrl }}" rel="noopener" target="_blank">
                        <span class="inove-icon inove-icon--fill" aria-hidden="true">
                            <svg height="1em" viewBox="0 0 496 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M248,8C111.033,8,0,119.033,0,256S111.033,504,248,504,496,392.967,496,256,384.967,8,248,8ZM362.952,176.66c-3.732,39.215-19.881,134.378-28.1,178.3-3.476,18.584-10.322,24.816-16.948,25.425-14.4,1.326-25.338-9.517-39.287-18.661-21.827-14.308-34.158-23.215-55.346-37.177-24.485-16.135-8.612-25,5.342-39.5,3.652-3.793,67.107-61.51,68.335-66.746.153-.655.3-3.1-1.154-4.384s-3.59-.849-5.135-.5q-3.283.746-104.608,69.142-14.845,10.194-26.894,9.934c-8.855-.191-25.888-5.006-38.551-9.123-15.531-5.048-27.875-7.717-26.8-16.291q.84-6.7,18.45-13.7,108.446-47.248,144.628-62.3c68.872-28.647,83.183-33.623,92.511-33.789,2.052-.034,6.639.474,9.61,2.885a10.452,10.452,0,0,1,3.53,6.716A43.765,43.765,0,0,1,362.952,176.66Z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!---->
        <div data-v-a32747ca="" data-v-d6b2e344="" class="footer-section text-section">
            <div data-v-a32747ca="" class="text-[.7rem] text-footer-texts text-center overflow-hidden pb-2 min-w-full max-h-16">
                <div data-v-a32747ca="" class="footer-text-dynamic">
                    <h2>
                        {{ $footerSettings->footer_text }}
                    </h2>
                    <p><br /></p>
                    <h3><p>{{ $footerSettings->footer_subtext }}</p></h3>
                </div>
            </div>
            <button data-v-a32747ca="" class="toggle-btn">Ver mais</button>
        </div>
        <div data-v-d6b2e344="" class="C59wU">
            <img
                alt="{{ $flagAlt ?? 'BRA' }}"
                loading="lazy"
                data-inove-img=""
                class="AlabW"
                src="{{ completeImageUrl($flagImage ?? 'img/flags/BRA.svg') }}"
            />
            <p>{{ __('footer.made_with_love') }}</p>
        </div>
        @if($showAutorizadoCassino)
            <div data-v-daa8f967="" data-v-d6b2e344="" class="footer-section autorizado-cassino">
                <div data-v-daa8f967="" class="register-section">
                    <p data-v-daa8f967="">
                        <strong>{{ $siteInfo->name ?? config('app.name') }}</strong> é um site de entretenimento online que oferece aos seus usuários uma experiência única em apostas esportivas e jogos online. Este site é operado por uma empresa devidamente registrada no Brasil e autorizada a operar na modalidade lotérica de apostas de quota fixa, proporcionando ação em diversas temáticas esportivas e uma variedade de jogos online, sempre com a segurança e confiabilidade necessárias.<br />
                        <br />
                        Ao acessar, continuar a usar ou navegar neste site, você concorda que podemos usar determinados cookies do navegador para melhorar sua experiência ao usar nosso site. Utilizamos cookies para personalizar conteúdo e anúncios, fornecer recursos de redes sociais e analisar nosso tráfego, sem interferir na sua privacidade.
                    </p>
                </div>
                <div data-v-daa8f967="" class="inove-license__seals">
                    <!----><!----><!----><!----><!----><!---->
                    <div style="display: inline-flex; width: 256px;">
                        <a href="https://www.gov.br/fazenda/pt-br/composicao/orgaos/secretaria-de-premios-e-apostas/lista-de-empresas/confira-a-lista-de-empresas-autorizadas-a-ofertar-apostas-de-quota-fixa-em-2025" rel="noopener" target="_blank">
                            <img alt="autorizado" class="m-auto w-full h-auto" src="{{ completeImageUrl('img/license/autorizado.png') }}" />
                        </a>
                    </div>
                    <!---->
                </div>
            </div>
        @endif
        <div data-v-d6b2e344="" class="pIDDI">
            <a href="https://www.begambleaware.org/" rel="noopener" target="_blank" class="be-glamble-aware">
                <img alt="BeGambleAware" class="pBDYo" src="{{ completeImageUrl('img/license/BeGambleAware.svg') }}" title="BeGambleAware" />
            </a>
            <a href="https://www.gamblingtherapy.org/pt-br/" rel="noopener" target="_blank" class="gt-logo">
                <img alt="Terapia de jogo" class="pBDYo" src="{{ completeImageUrl('img/license/gt_logo.png') }}" title="Terapia de jogo" />
            </a>
            <a href="{{ route('responsible.gaming') }}" class="ZU59j">
                <svg fill="none" height="1em" viewBox="0 0 200 200" width="1em" xmlns="http://www.w3.org/2000/svg" class="GtvgE" title="Para maiores de 18 anos">
                    <circle cx="100" cy="100" fill="currentColor" r="100"></circle>
                    <circle cx="99.9231" cy="99.9231" fill="black" r="76.9231"></circle>
                    <path d="M45 91.304V76.2294C53.0312 74.8205 58.788 70.3122 62.2706 62.7044H72.7182V136.951H58.6459V84.4007C55.803 87.3123 51.2544 89.6134 45 91.304Z" fill="white"></path>
                    <path
                        d="M82.9527 97.0803C78.9726 93.4173 76.9826 88.2985 76.9826 81.7239C76.9826 76.5581 78.5462 72.0028 81.6733 68.058C84.8716 64.0193 89.7756 62 96.3853 62C102.711 62 107.508 63.9724 110.777 67.9172C114.118 71.768 115.788 76.3702 115.788 81.7239C115.788 88.2985 113.798 93.4173 109.818 97.0803C115.575 101.213 118.453 107.365 118.453 115.536C118.453 122.205 116.428 127.793 112.377 132.301C108.325 136.716 102.995 138.923 96.3853 138.923C89.7045 138.923 84.3386 136.669 80.2874 132.161C76.3074 127.652 74.3174 122.111 74.3174 115.536C74.3174 111.122 75.1702 107.365 76.876 104.265C78.5817 101.072 80.6073 98.677 82.9527 97.0803ZM96.3853 91.5858C98.02 91.5858 99.477 90.9753 100.756 89.7543C102.036 88.4394 102.675 86.5139 102.675 83.978C102.675 81.3482 102.036 79.3758 100.756 78.0609C99.477 76.7459 98.02 76.0885 96.3853 76.0885C94.7507 76.0885 93.2581 76.7459 91.9078 78.0609C90.6285 79.3758 89.9888 81.3482 89.9888 83.978C89.9888 86.42 90.6285 88.2985 91.9078 89.6134C93.2581 90.9283 94.7507 91.5858 96.3853 91.5858ZM96.3853 124.271C98.8018 124.271 100.721 123.379 102.142 121.594C103.635 119.81 104.381 117.509 104.381 114.691C104.381 111.685 103.599 109.243 102.036 107.365C100.543 105.393 98.6596 104.406 96.3853 104.406C94.0399 104.406 92.121 105.393 90.6285 107.365C89.1359 109.243 88.3897 111.685 88.3897 114.691C88.3897 117.603 89.1004 119.951 90.5219 121.735C92.0144 123.426 93.9689 124.271 96.3853 124.271Z"
                        fill="white"
                    ></path>
                    <path
                        d="M143.66 85.0769V84.0769H142.66H138.264H137.264V85.0769V97.3553H125.077H124.077V98.3553V102.659V103.659H125.077H137.264V115.846V116.846H138.264H142.66H143.66V115.846V103.659H155.846H156.846V102.659V98.3553H155.846H143.66V85.0769Z"
                        fill="white"
                        stroke="white"
                        stroke-width="2"
                    ></path>
                </svg>
                <span>Jogue com responsabilidade</span>
            </a>
        </div>
        @php
            if (!isset($emails)) {
                $domain = parse_url(URL::to('/'), PHP_URL_HOST);
                $emails = [
                    'support' => "suporte@{$domain}",
                    'contact' => "contato@{$domain}",
                    'atendimento' => $footerSettings->support_email ?? "atendimento@{$domain}"
                ];
            }
        @endphp
        <div data-v-d6b2e344="" class="K5Zta">
            <a class="BH30M _0Ua6s" href="{{ $footerSettings->ouvidoria_url ?? 'https://ajuda.inove.com' }}" target="_blank"><span>Ouvidoria</span></a>
            <a class="BH30M _0Ua6s" href="{{ $footerSettings->denuncias_url ?? 'https://ajuda.inove.com' }}" target="_blank"><span>Denúncias</span></a>
            <a class="BH30M _0Ua6s" href="{{ $footerSettings->suporte_jogador_url ?? 'https://ajuda.inove.com' }}" target="_blank"><span>Suporte ao Jogador</span></a>
            <p class="_0Ua6s">
                <span>Suporte</span><a class="BH30M" href="mailto:{{ $emails['atendimento'] }}"><span>{{ $emails['atendimento'] }}</span></a>
            </p>
        </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.querySelector('.toggle-btn');
        // Seletor mais confiável usando o atributo data-v e a classe text-center
        const footerTextContainer = document.querySelector('[data-v-a32747ca].text-center');
        const footerTextDynamic = document.querySelector('.footer-text-dynamic');
        const h3Element = footerTextDynamic ? footerTextDynamic.querySelector('h3') : null;

        // Ocultar o h3 inicialmente
        if (h3Element) {
            h3Element.style.display = 'none';
        }

        if (toggleBtn && footerTextContainer && footerTextDynamic) {
            toggleBtn.addEventListener('click', function() {
                const isExpanded = toggleBtn.classList.contains('open');

                if (isExpanded) {
                    // Recolher o conteúdo
                    footerTextContainer.classList.remove('max-h-full', 'mb-2');
                    footerTextContainer.classList.add('max-h-16');
                    toggleBtn.classList.remove('open');
                    toggleBtn.textContent = 'Ver mais';

                    // Ocultar o h3
                    if (h3Element) {
                        h3Element.style.display = 'none';
                    }
                } else {
                    // Expandir o conteúdo
                    footerTextContainer.classList.remove('max-h-16');
                    footerTextContainer.classList.add('max-h-full', 'mb-2');
                    toggleBtn.classList.add('open');
                    toggleBtn.textContent = 'Ver menos';

                    // Mostrar o h3
                    if (h3Element) {
                        h3Element.style.display = 'block';
                    }
                }
            });
        }
    });

    // Função para alternar a visibilidade da div conforme a resolução
    document.addEventListener('DOMContentLoaded', function() {
        const xlDiv = document.querySelector('[data-v-ec39842a].xl\\:flex');

        function toggleHiddenClass() {
            if (window.innerWidth < 1280) {
                // Tela menor que 1280px (xl breakpoint) - adicionar hidden
                if (!xlDiv.classList.contains('hidden')) {
                    xlDiv.classList.add('hidden');
                }
            } else {
                // Tela >= 1280px - remover hidden
                if (xlDiv.classList.contains('hidden')) {
                    xlDiv.classList.remove('hidden');
                }
            }
        }

        // Verificar ao carregar
        if (xlDiv) {
            toggleHiddenClass();

            // Adicionar listener para redimensionamento da janela
            window.addEventListener('resize', toggleHiddenClass);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Obter o timestamp de login do servidor
        const loginTimestamp = {{ $loginTimestamp ?? 'null' }};

        // Se não tiver timestamp, não iniciar o contador
        if (!loginTimestamp) return;

        // Função para atualizar o contador
        function updateCounter() {
            const now = Math.floor(Date.now() / 1000); // Tempo atual em segundos
            const diff = now - loginTimestamp;

            const hours = Math.floor(diff / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = diff % 60;

            // Formatar o tempo
            const timeFormatted = `${String(hours).padStart(2, '0')}h ${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}s`;

            // Atualizar o elemento HTML
            if (document.getElementById('timeLoggedCounter')) {
                document.getElementById('timeLoggedCounter').textContent = timeFormatted;
            }
        }

        // Atualizar imediatamente e depois a cada segundo
        updateCounter();
        setInterval(updateCounter, 1000);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const jg6dgElement = document.querySelector('.JG6dg');

        function toggleHiddenClass() {
            if (window.innerWidth < 767) {
                // Tela menor que 767px - adicionar hidden
                if (!jg6dgElement.classList.contains('hidden')) {
                    jg6dgElement.classList.add('hidden');
                }
            } else {
                // Tela >= 767px - remover hidden
                if (jg6dgElement.classList.contains('hidden')) {
                    jg6dgElement.classList.remove('hidden');
                }
            }
        }

        // Verificar ao carregar
        if (jg6dgElement) {
            toggleHiddenClass();

            // Adicionar listener para redimensionamento da janela
            window.addEventListener('resize', toggleHiddenClass);
        }
    });
</script>
