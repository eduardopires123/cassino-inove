@php
    $Infos = App\Helpers\Core::getSetting();
    
    // Verificar qual provedor de API de sports está ativo
    $sportsApiProvider = App\Models\Settings::getSportsApiProvider();
    $sportsRoute = \App\Models\Settings::isBetbyActive() ? route('sports.betby') : route('esportes'); // Rota específica para cada sistema
@endphp

<!-- Menu mobile padrão -->
<div data-v-4af8cbc5="" id="divMobileMenu" class="mobileMenuContainer">
    <div data-v-4af8cbc5="" class="mobileMenuWrapper">
        <div data-v-4af8cbc5="" class="mobileMenu">
        <button data-v-4af8cbc5="" id="btnMobileMenu" class="btn">
            <span data-v-4af8cbc5="" class="nuxt-icon nuxt-icon--fill nuxt-icon--stroke icon">
                    <svg width="26" height="20" viewBox="0 0 26 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 10H21.5" stroke="#8B8FB1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path opacity="0.6" d="M4 4H21.5" stroke="#585B72" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path opacity="0.6" d="M4 16H21.5" stroke="#585B72" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
                {{ __('messages.menu') }}
            </button>
            <a data-v-4af8cbc5="" href="{{ route('cassino.slots') }}" class="btn">
                <span data-v-4af8cbc5="" class="nuxt-icon nuxt-icon--fill icon" aria-hidden="true">
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_2269_1785)">
                            <path
                                opacity="0.6"
                                d="M2.25 2.5H23.25C23.5152 2.5 23.7696 2.60536 23.9571 2.79289C24.1446 2.98043 24.25 3.23478 24.25 3.5V5.5H1.25V3.5C1.25 3.23478 1.35536 2.98043 1.54289 2.79289C1.73043 2.60536 1.98478 2.5 2.25 2.5Z"
                                fill="#585B72"
                            ></path>
                            <path
                                opacity="0.6"
                                d="M1.25 18.5H24.25V20.5C24.25 20.7652 24.1446 21.0196 23.9571 21.2071C23.7696 21.3946 23.5152 21.5 23.25 21.5H2.25C1.98478 21.5 1.73043 21.3946 1.54289 21.2071C1.35536 21.0196 1.25 20.7652 1.25 20.5V18.5Z"
                                fill="#585B72"
                            ></path>
                            <path
                                d="M8.25 17.5V6.5H1.25V17.5H8.25ZM2.25 8.75C2.25 8.6837 2.27634 8.62011 2.32322 8.57322C2.37011 8.52634 2.4337 8.5 2.5 8.5H7C7.0663 8.5 7.12989 8.52634 7.17678 8.57322C7.22366 8.62011 7.25 8.6837 7.25 8.75V10.358C7.24979 10.4002 7.23902 10.4416 7.21867 10.4786C7.19832 10.5155 7.16904 10.5468 7.1335 10.5695C6.718 10.8395 5.3335 12.4485 5.2535 15.2525C5.2535 15.3184 5.22751 15.3816 5.18116 15.4284C5.13482 15.4752 5.07187 15.5018 5.006 15.5025H3.0105C2.97643 15.5029 2.94262 15.4964 2.91116 15.4833C2.87971 15.4702 2.85126 15.4508 2.82756 15.4264C2.80387 15.4019 2.78543 15.3728 2.77338 15.3409C2.76133 15.309 2.75593 15.275 2.7575 15.241C2.9235 12.3845 5.75 10 5.75 10H3.75C3.61739 10 3.49021 10.0527 3.39645 10.1464C3.30268 10.2402 3.25 10.3674 3.25 10.5V10.75C3.25 10.8163 3.22366 10.8799 3.17678 10.9268C3.12989 10.9737 3.0663 11 3 11H2.5C2.4337 11 2.37011 10.9737 2.32322 10.9268C2.27634 10.8799 2.25 10.8163 2.25 10.75V8.75Z"
                                fill="#8B8FB1"
                            ></path>
                            <path
                                d="M24.25 17.5V6.5H17.25V17.5H24.25ZM18.25 8.75C18.25 8.6837 18.2763 8.62011 18.3232 8.57322C18.3701 8.52634 18.4337 8.5 18.5 8.5H23C23.0663 8.5 23.1299 8.52634 23.1768 8.57322C23.2237 8.62011 23.25 8.6837 23.25 8.75V10.358C23.2498 10.4002 23.239 10.4416 23.2187 10.4786C23.1983 10.5155 23.169 10.5468 23.1335 10.5695C22.718 10.8395 21.331 12.4485 21.2535 15.2525C21.2535 15.3184 21.2275 15.3816 21.1812 15.4284C21.1348 15.4752 21.0719 15.5018 21.006 15.5025H19.0105C18.9764 15.5029 18.9426 15.4964 18.9112 15.4833C18.8797 15.4702 18.8513 15.4508 18.8276 15.4264C18.8039 15.4019 18.7854 15.3728 18.7734 15.3409C18.7613 15.309 18.7559 15.275 18.7575 15.241C18.9235 12.3845 21.75 10 21.75 10H19.75C19.6174 10 19.4902 10.0527 19.3964 10.1464C19.3027 10.2402 19.25 10.3674 19.25 10.5V10.75C19.25 10.8163 19.2237 10.8799 19.1768 10.9268C19.1299 10.9737 19.0663 11 19 11H18.5C18.4337 11 18.3701 10.9737 18.3232 10.9268C18.2763 10.8799 18.25 10.8163 18.25 10.75V8.75Z"
                                fill="#8B8FB1"
                            ></path>
                            <path
                                d="M16.25 17.5V6.5H9.25V17.5H16.25ZM10.25 8.75C10.25 8.6837 10.2763 8.62011 10.3232 8.57322C10.3701 8.52634 10.4337 8.5 10.5 8.5H15C15.0663 8.5 15.1299 8.52634 15.1768 8.57322C15.2237 8.62011 15.25 8.6837 15.25 8.75V10.358C15.2498 10.4002 15.239 10.4416 15.2187 10.4786C15.1983 10.5155 15.169 10.5468 15.1335 10.5695C14.718 10.8395 13.331 12.4485 13.2535 15.2525C13.2535 15.3188 13.2272 15.3824 13.1803 15.4293C13.1334 15.4762 13.0698 15.5025 13.0035 15.5025H11.0105C10.9764 15.5029 10.9426 15.4964 10.9112 15.4833C10.8797 15.4702 10.8513 15.4508 10.8276 15.4264C10.8039 15.4019 10.7854 15.3728 10.7734 15.3409C10.7613 15.309 10.7559 15.275 10.7575 15.241C10.9235 12.3845 13.75 10 13.75 10H11.75C11.6174 10 11.4902 10.0527 11.3964 10.1464C11.3027 10.2402 11.25 10.3674 11.25 10.5V10.75C11.25 10.8163 11.2237 10.8799 11.1768 10.9268C11.1299 10.9737 11.0663 11 11 11H10.5C10.4337 11 10.3701 10.9737 10.3232 10.9268C10.2763 10.8799 10.25 10.8163 10.25 10.75V8.75Z"
                                fill="#8B8FB1"
                            ></path>
                        </g>
                        <defs>
                            <clipPath id="clip0_2269_1785">
                                <rect width="24" height="24" fill="white" transform="translate(0.75)"></rect>
                            </clipPath>
                        </defs>
                    </svg>
                </span>
                Slots
            </a>

            <button data-v-4af8cbc5="" class="btn" data-action="deposit">
                <span data-v-4af8cbc5="" class="nuxt-icon nuxt-icon--fill icon" aria-hidden="true">
                    <svg width="30" height="32" viewBox="0 0 30 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#filter0_d_2208_893)">
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M16.7853 20.3557V24.4941C16.7853 24.6757 16.988 25.1319 18.0049 25.5941C18.9413 26.0198 20.2976 26.3064 21.8434 26.3064C23.3892 26.3064 24.7454 26.0198 25.6818 25.5941C26.6987 25.1319 26.9015 24.6757 26.9015 24.4941V20.3557H28.6867V24.4941C28.6867 25.8063 27.5573 26.7026 26.4205 27.2193C25.2031 27.7727 23.584 28.0916 21.8434 28.0916C20.1027 28.0916 18.4836 27.7727 17.2662 27.2193C16.1294 26.7026 15.0001 25.8063 15.0001 24.4941V20.3557H16.7853Z"
                                fill="#E4E6E1"
                            ></path>
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M21.8434 16.7853C20.0495 16.7853 18.3963 17.1303 17.1693 17.7145C15.9867 18.2777 15.0001 19.1719 15.0001 20.3557C15.0001 21.5395 15.9867 22.4337 17.1693 22.9969C18.3963 23.5811 20.0495 23.9261 21.8434 23.9261C23.6372 23.9261 25.2904 23.5811 26.5174 22.9969C27.7 22.4337 28.6867 21.5395 28.6867 20.3557C28.6867 19.1719 27.7 18.2777 26.5174 17.7145C25.2904 17.1303 23.6372 16.7853 21.8434 16.7853ZM19.7606 19.4631V21.2483H23.9261V19.4631H19.7606Z"
                                fill="#E4E6E1"
                            ></path>
                            <path
                                d="M28.6867 3.09863H1.31342V22.1409H13.2173C13.2163 21.548 13.2148 20.9548 13.2148 20.3557H3.09863V4.88384H26.9014V15.931C27.0319 15.9859 27.1598 16.0431 27.2849 16.1027C27.7234 16.3115 28.2191 16.5976 28.6867 16.9687V3.09863Z"
                                fill="#E4E6E1"
                            ></path>
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M4.88385 8.45428C5.86979 8.45428 6.66906 7.65504 6.66906 6.66907H23.331C23.331 7.65504 24.1303 8.45428 25.1162 8.45428V15.361C24.0899 15.1228 22.9783 15.0001 21.8434 15.0001C19.8442 15.0001 17.9176 15.381 16.4018 16.1027C15.4662 16.5482 14.2703 17.3453 13.643 18.5705H6.66906C6.66906 17.5845 5.86979 16.7853 4.88385 16.7853V8.45428ZM15 15.5951C16.6433 15.5951 17.9754 14.263 17.9754 12.6198C17.9754 10.9765 16.6433 9.64442 15 9.64442C13.3568 9.64442 12.0247 10.9765 12.0247 12.6198C12.0247 14.263 13.3568 15.5951 15 15.5951ZM23.0335 11.7272H20.0581V13.5124H23.0335V11.7272ZM6.9666 13.5124V11.7272H9.94195V13.5124H6.9666Z"
                                fill="#E4E6E1"
                            ></path>
                        </g>
                        <defs>
                            <filter id="filter0_d_2208_893" x="1.31342" y="3.09863" width="27.3733" height="27.9929" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix>
                                <feOffset dy="3"></feOffset>
                                <feComposite in2="hardAlpha" operator="out"></feComposite>
                                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"></feColorMatrix>
                                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2208_893"></feBlend>
                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2208_893" result="shape"></feBlend>
                            </filter>
                        </defs>
                    </svg>
                </span>
                Depositar
            </button>
        @if ($Infos->enable_sports === 1)
            <button data-v-4af8cbc5="" class="btn" onclick="window.location.href='{{ $sportsRoute }}'">
                <span data-v-4af8cbc5="" class="nuxt-icon nuxt-icon--fill icon" aria-hidden="true">
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_2269_1776)">
                            <path d="M24.0682 14.781C22.936 14.6028 22.0672 13.6203 22.0672 12.4389L22.0672 11.6023C22.0672 10.4207 22.936 9.43817 24.0682 9.26001L24.25 9.26001L24.25 14.781L24.0682 14.781Z" fill="#8B8FB1"></path>
                            <path
                                opacity="0.6"
                                d="M13.3402 8.23187C15.1306 8.56348 16.4911 10.1356 16.4911 12.0205C16.4911 13.9054 15.1306 15.4775 13.3402 15.8091L13.3402 21.873C13.3402 21.9254 13.334 21.9763 13.3232 22.0254L20.7344 22.0254C22.6729 22.0254 24.25 20.4483 24.25 18.5098L24.25 16.2114C22.2543 16.1129 20.6609 14.4584 20.6609 12.4389L20.6609 11.6023C20.6609 9.58264 22.2543 7.9281 24.25 7.82977L24.25 5.53125C24.25 3.59271 22.6729 2.01562 20.7344 2.01562L13.3232 2.01562C13.334 2.0647 13.3402 2.1156 13.3402 2.16797L13.3402 8.23187Z"
                                fill="#585B72"
                            ></path>
                            <path
                                opacity="0.6"
                                d="M11.934 15.8091C10.1436 15.4775 8.7829 13.9054 8.7829 12.0205C8.7829 10.1356 10.1436 8.56348 11.934 8.23187L11.934 2.16797C11.934 2.1156 11.94 2.0647 11.951 2.01562L3.76562 2.01562C1.82709 2.01562 0.25 3.59271 0.25 5.53125L0.25 7.82977C2.24567 7.9281 3.83905 9.58264 3.83905 11.6023L3.83905 12.4389C3.83905 14.4586 2.24567 16.1129 0.249999 16.2114L0.249999 18.5098C0.249999 20.4483 1.82709 22.0254 3.76562 22.0254L11.951 22.0254C11.94 21.9763 11.934 21.9254 11.934 21.873L11.934 15.8091Z"
                                fill="#585B72"
                            ></path>
                            <path d="M2.4328 12.4388L2.4328 11.6022C2.4328 10.3582 1.46967 9.33557 0.25 9.23926L0.25 14.8016C1.46967 14.7053 2.4328 13.6827 2.4328 12.4388Z" fill="#8B8FB1"></path>
                            <path d="M13.3402 14.3649C14.3482 14.0619 15.0848 13.1259 15.0848 12.0204C15.0848 10.915 14.3482 9.979 13.3402 9.67615L13.3402 14.3649Z" fill="#8B8FB1"></path>
                            <path d="M11.934 9.67615C10.926 9.979 10.1891 10.915 10.1891 12.0204C10.1891 13.1259 10.926 14.0619 11.934 14.3649L11.934 9.67615Z" fill="#8B8FB1"></path>
                        </g>
                        <defs>
                            <clipPath id="clip0_2269_1776">
                                <rect width="24" height="24" fill="white" transform="translate(24.25) rotate(90)"></rect>
                            </clipPath>
                        </defs>
                    </svg>
                </span>
                {{ __('messages.esportes') }}
            </button>
        @endif

            <a data-v-4af8cbc5="" href="{{ route('cassino.todos-jogos') }}" class="btn">
                <span data-v-4af8cbc5="" class="nuxt-icon nuxt-icon--fill icon" aria-hidden="true">
                    <svg height="1em" viewBox="0 0 640 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M591.1 192l-118.7 0c4.418 10.27 6.604 21.25 6.604 32.23c0 20.7-7.865 41.38-23.63 57.14l-136.2 136.2v46.37C320 490.5 341.5 512 368 512h223.1c26.5 0 47.1-21.5 47.1-47.1V240C639.1 213.5 618.5 192 591.1 192zM479.1 376c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1s23.1 10.75 23.1 23.1S493.2 376 479.1 376zM96 200c-13.25 0-23.1 10.75-23.1 23.1s10.75 23.1 23.1 23.1s23.1-10.75 23.1-23.1S109.3 200 96 200zM352 248c13.25 0 23.1-10.75 23.1-23.1s-10.75-23.1-23.1-23.1S328 210.8 328 224S338.8 248 352 248zM224 328c-13.25 0-23.1 10.75-23.1 23.1s10.75 23.1 23.1 23.1c13.25 0 23.1-10.75 23.1-23.1S237.3 328 224 328zM224 200c-13.25 0-23.1 10.75-23.1 23.1s10.75 23.1 23.1 23.1s23.1-10.75 23.1-23.1S237.3 200 224 200zM224 72c-13.25 0-23.1 10.75-23.1 23.1s10.75 23.1 23.1 23.1c13.25 0 23.1-10.75 23.1-23.1S237.3 72 224 72z"
                            fill="currentColor"
                        ></path>
                        <path
                            d="M447.1 224c0-12.56-4.782-25.13-14.35-34.76l-174.9-174.9C249.1 4.784 236.5 0 223.1 0C211.4 0 198.9 4.784 189.2 14.35L14.35 189.2C4.784 198.9-.0011 211.4-.0011 223.1c0 12.56 4.786 25.18 14.35 34.8l174.9 174.9c9.626 9.563 22.19 14.35 34.75 14.35c12.56 0 25.13-4.782 34.75-14.35l174.9-174.9C443.2 249.1 447.1 236.6 447.1 224zM96 248c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1s23.1 10.75 23.1 23.1S109.3 248 96 248zM224 376c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1c13.25 0 23.1 10.75 23.1 23.1S237.3 376 224 376zM224 248c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1s23.1 10.75 23.1 23.1S237.3 248 224 248zM224 120c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1c13.25 0 23.1 10.75 23.1 23.1S237.3 120 224 120zM352 248c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1s23.1 10.75 23.1 23.1S365.3 248 352 248z"
                            fill="currentColor"
                            opacity="0.4"
                        ></path>
                    </svg>
                </span>
                {{ __('messages.cassino') }}
            </a>
        </div>
    </div>
    <button data-v-4af8cbc5="" class="btn-deposit" data-action="deposit"><span data-v-4af8cbc5="" class="nuxt-icon nuxt-icon--fill icon" aria-hidden="true"><svg width="30" height="32" viewBox="0 0 30 32" fill="none" xmlns="http://www.w3.org/2000/svg">
    <g filter="url(#filter0_d_2208_893)">
    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.7853 20.3557V24.4941C16.7853 24.6757 16.988 25.1319 18.0049 25.5941C18.9413 26.0198 20.2976 26.3064 21.8434 26.3064C23.3892 26.3064 24.7454 26.0198 25.6818 25.5941C26.6987 25.1319 26.9015 24.6757 26.9015 24.4941V20.3557H28.6867V24.4941C28.6867 25.8063 27.5573 26.7026 26.4205 27.2193C25.2031 27.7727 23.584 28.0916 21.8434 28.0916C20.1027 28.0916 18.4836 27.7727 17.2662 27.2193C16.1294 26.7026 15.0001 25.8063 15.0001 24.4941V20.3557H16.7853Z" fill="#E4E6E1"></path>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M21.8434 16.7853C20.0495 16.7853 18.3963 17.1303 17.1693 17.7145C15.9867 18.2777 15.0001 19.1719 15.0001 20.3557C15.0001 21.5395 15.9867 22.4337 17.1693 22.9969C18.3963 23.5811 20.0495 23.9261 21.8434 23.9261C23.6372 23.9261 25.2904 23.5811 26.5174 22.9969C27.7 22.4337 28.6867 21.5395 28.6867 20.3557C28.6867 19.1719 27.7 18.2777 26.5174 17.7145C25.2904 17.1303 23.6372 16.7853 21.8434 16.7853ZM19.7606 19.4631V21.2483H23.9261V19.4631H19.7606Z" fill="#E4E6E1"></path>
    <path d="M28.6867 3.09863H1.31342V22.1409H13.2173C13.2163 21.548 13.2148 20.9548 13.2148 20.3557H3.09863V4.88384H26.9014V15.931C27.0319 15.9859 27.1598 16.0431 27.2849 16.1027C27.7234 16.3115 28.2191 16.5976 28.6867 16.9687V3.09863Z" fill="#E4E6E1"></path>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.88385 8.45428C5.86979 8.45428 6.66906 7.65504 6.66906 6.66907H23.331C23.331 7.65504 24.1303 8.45428 25.1162 8.45428V15.361C24.0899 15.1228 22.9783 15.0001 21.8434 15.0001C19.8442 15.0001 17.9176 15.381 16.4018 16.1027C15.4662 16.5482 14.2703 17.3453 13.643 18.5705H6.66906C6.66906 17.5845 5.86979 16.7853 4.88385 16.7853V8.45428ZM15 15.5951C16.6433 15.5951 17.9754 14.263 17.9754 12.6198C17.9754 10.9765 16.6433 9.64442 15 9.64442C13.3568 9.64442 12.0247 10.9765 12.0247 12.6198C12.0247 14.263 13.3568 15.5951 15 15.5951ZM23.0335 11.7272H20.0581V13.5124H23.0335V11.7272ZM6.9666 13.5124V11.7272H9.94195V13.5124H6.9666Z" fill="#E4E6E1"></path>
    </g>
    <defs>
    <filter id="filter0_d_2208_893" x="1.31342" y="3.09863" width="27.3733" height="27.9929" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
    <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix>
    <feOffset dy="3"></feOffset>
    <feComposite in2="hardAlpha" operator="out"></feComposite>
    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"></feColorMatrix>
    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2208_893"></feBlend>
    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2208_893" result="shape"></feBlend>
    </filter>
    </defs>
    </svg>
    </span></button>
</div>

<!-- Menu mobile específico para página de Sports -->
<div data-v-4af8cbc5="" id="divMobileMenuSports" class="mobileMenuContainer">
    <div data-v-4af8cbc5="" class="mobileMenuWrapper">
        <div data-v-4af8cbc5="" class="mobileMenu">
            <button data-v-4af8cbc5="" class="btn" id="mn-mb-01">
                <span data-v-4af8cbc5="" class="nuxt-icon nuxt-icon--fill icon">
                    <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z" fill="currentColor"></path>
                    </svg>
                </span> Menu
            </button>
            <button data-v-4af8cbc5="" class="btn active" id="mn-mb-02">
                <span data-v-4af8cbc5="" class="nuxt-icon nuxt-icon--fill icon" aria-hidden="true">
                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M355.5 45.53L342.4 14.98c-27.95-9.983-57.18-14.98-86.42-14.98c-29.25 0-58.51 4.992-86.46 14.97L156.5 45.53l99.5 55.13L355.5 45.53zM86.78 96.15L53.67 99.09c-34.79 44.75-53.67 99.8-53.67 156.5L.0001 256c0 2.694 .0519 5.379 .1352 8.063l24.95 21.76l83.2-77.67L86.78 96.15zM318.8 336L357.3 217.4L255.1 144L154.7 217.4l38.82 118.6L318.8 336zM512 255.6c0-56.7-18.9-111.8-53.72-156.5L425.6 96.16L403.7 208.2l83.21 77.67l24.92-21.79C511.1 260.1 512 258.1 512 255.6zM51.77 367.7l-7.39 32.46c33.48 49.11 82.96 85.07 140 101.7l28.6-16.99l-48.19-103.3L51.77 367.7zM347.2 381.5l-48.19 103.3l28.57 17c57.05-16.66 106.5-52.62 140-101.7l-7.38-32.46L347.2 381.5z" fill="currentColor"></path>
                        <path d="M458.3 99.08L458.3 99.08L458.3 99.08zM511.8 264c-1.442 48.66-16.82 95.87-44.28 136.1l-7.38-32.46l-113 13.86l-48.19 103.3l28.22 16.84c-23.48 6.78-47.67 10.2-71.85 10.2c-23.76 0-47.51-3.302-70.58-9.962l28.23-17.06l-48.19-103.3l-113-13.88l-7.39 32.46c-27.45-40.19-42.8-87.41-44.25-136.1l24.95 21.76l83.2-77.67L86.78 96.15L53.67 99.09c29.72-38.29 69.67-67.37 115.2-83.88l.3613 .2684L156.5 45.53l99.5 55.13l99.5-55.13L342.4 14.98c45.82 16.48 86 45.64 115.9 84.11L425.6 96.16L403.7 208.2l83.21 77.67L511.8 264zM357.3 217.4L255.1 144L154.7 217.4l38.82 118.6L318.8 336L357.3 217.4z" fill="currentColor" opacity="0.4"></path>
                    </svg>
                </span> Esportes
            </button>
            <a data-v-4af8cbc5="" href="/sports/live" class="btn" id="mn-mb-03" data-text-alternate="Esportes">
                <span data-v-4af8cbc5="" class="badge-live dot"></span>
                <span data-v-4af8cbc5="" class="nuxt-icon nuxt-icon--fill icon" aria-hidden="true">
                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path class="primary" d="M201.9 32l-128 128h92.13l128-128H201.9zM64 32C28.65 32 0 60.65 0 96v64h6.062l128-128H64zM326.1 160l127.4-127.4C451.7 32.39 449.9 32 448 32h-86.06l-128 128H326.1zM497.7 56.19L393.9 160H512V96C512 80.87 506.5 67.15 497.7 56.19zM224.3 241.7C221.1 239.5 216.9 239.5 213.5 241.4C210.1 243.3 208 247 208 251v137.9c0 4.008 2.104 7.705 5.5 9.656C215.1 399.5 216.9 400 218.7 400c1.959 0 3.938-.5605 5.646-1.682l106.7-68.97C334.1 327.3 336 323.8 336 319.1s-1.896-7.34-5.021-9.354L224.3 241.7z" fill="currentColor"></path>
                        <path class="secondary" d="M0 160v256c0 35.35 28.65 64 64 64h384c35.35 0 64-28.65 64-64V160H0zM330.1 329.3l-106.7 68.97C222.6 399.4 220.6 400 218.7 400c-1.77 0-3.562-.4648-5.166-1.379C210.1 396.7 208 392.1 208 388.1V251c0-4.01 2.104-7.705 5.5-9.656c3.375-1.918 7.562-1.832 10.81 .3027l106.7 68.97C334.1 312.7 336 316.2 336 319.1S334.1 327.3 330.1 329.3z" fill="currentColor" opacity="0.4"></path>
                    </svg>
                </span>
                <span data-v-4af8cbc5="" class="btn-text-ao-vivo">Ao vivo</span>
            </a>
            <a data-v-4af8cbc5="" href="{{ route('cassino.todos-jogos') }}" class="btn" id="mn-mb-04">
                <span data-v-4af8cbc5="" class="nuxt-icon nuxt-icon--fill icon" aria-hidden="true">
                    <svg height="1em" viewBox="0 0 640 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M220.7 7.468C247.3-7.906 281.4 1.218 296.8 27.85L463.8 317.1C479.1 343.8 470 377.8 443.4 393.2L250.5 504.5C223.9 519.9 189.9 510.8 174.5 484.2L7.468 194.9C-7.906 168.2 1.218 134.2 27.85 118.8L220.7 7.468zM143.8 277.3C136.9 303.2 152.3 329.1 178.3 336.9C204.3 343.9 230.1 328.5 237.9 302.5L240.3 293.6C240.4 293.3 240.5 292.9 240.6 292.5L258.4 323.2L246.3 330.2C239.6 334 237.4 342.5 241.2 349.2C245.1 355.9 253.6 358.1 260.2 354.3L308.4 326.5C315.1 322.6 317.4 314.1 313.5 307.4C309.7 300.8 301.2 298.5 294.5 302.3L282.5 309.3L264.7 278.6C265.1 278.7 265.5 278.8 265.9 278.9L274.7 281.2C300.7 288.2 327.4 272.8 334.4 246.8C341.3 220.8 325.9 194.1 299.9 187.1L196.1 159.6C185.8 156.6 174.4 163.2 171.4 174.3L143.8 277.3z" fill="currentColor"></path>
                        <path d="M324.1 499L459.4 420.9C501.3 396.7 515.7 343.1 491.5 301.1L354.7 64.25C356.5 64.08 358.2 64 360 64H584C614.9 64 640 89.07 640 120V456C640 486.9 614.9 512 584 512H360C346.4 512 333.8 507.1 324.1 499V499zM579.8 135.7C565.8 123.9 545.3 126.2 532.9 138.9L528.1 144.2L523.1 138.9C510.6 126.2 489.9 123.9 476.4 135.7C460.7 149.2 459.9 173.1 473.9 187.6L522.4 237.6C525.4 240.8 530.6 240.8 533.9 237.6L582 187.6C596 173.1 595.3 149.2 579.8 135.7H579.8z" fill="currentColor" opacity="0.4"></path>
                    </svg>
                </span> Cassino
            </a>
            <a data-v-4af8cbc5="" href="{{ route('cassino.ao-vivo') }}" class="btn" id="mn-mb-05" data-text-alternate="Cassino">
                <span data-v-4af8cbc5="" class="badge-live dot"></span>
                <span data-v-4af8cbc5="" class="nuxt-icon nuxt-icon--fill icon" aria-hidden="true">
                    <svg height="1em" viewBox="0 0 640 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M591.1 192l-118.7 0c4.418 10.27 6.604 21.25 6.604 32.23c0 20.7-7.865 41.38-23.63 57.14l-136.2 136.2v46.37C320 490.5 341.5 512 368 512h223.1c26.5 0 47.1-21.5 47.1-47.1V240C639.1 213.5 618.5 192 591.1 192zM479.1 376c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1s23.1 10.75 23.1 23.1S493.2 376 479.1 376zM96 200c-13.25 0-23.1 10.75-23.1 23.1s10.75 23.1 23.1 23.1s23.1-10.75 23.1-23.1S109.3 200 96 200zM352 248c13.25 0 23.1-10.75 23.1-23.1s-10.75-23.1-23.1-23.1S328 210.8 328 224S338.8 248 352 248zM224 328c-13.25 0-23.1 10.75-23.1 23.1s10.75 23.1 23.1 23.1c13.25 0 23.1-10.75 23.1-23.1S237.3 328 224 328zM224 200c-13.25 0-23.1 10.75-23.1 23.1s10.75 23.1 23.1 23.1s23.1-10.75 23.1-23.1S237.3 200 224 200zM224 72c-13.25 0-23.1 10.75-23.1 23.1s10.75 23.1 23.1 23.1c13.25 0 23.1-10.75 23.1-23.1S237.3 72 224 72z" fill="currentColor"></path>
                        <path d="M447.1 224c0-12.56-4.782-25.13-14.35-34.76l-174.9-174.9C249.1 4.784 236.5 0 223.1 0C211.4 0 198.9 4.784 189.2 14.35L14.35 189.2C4.784 198.9-.0011 211.4-.0011 223.1c0 12.56 4.786 25.18 14.35 34.8l174.9 174.9c9.626 9.563 22.19 14.35 34.75 14.35c12.56 0 25.13-4.782 34.75-14.35l174.9-174.9C443.2 249.1 447.1 236.6 447.1 224zM96 248c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1s23.1 10.75 23.1 23.1S109.3 248 96 248zM224 376c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1c13.25 0 23.1 10.75 23.1 23.1S237.3 376 224 376zM224 248c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1s23.1 10.75 23.1 23.1S237.3 248 224 248zM224 120c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1c13.25 0 23.1 10.75 23.1 23.1S237.3 120 224 120zM352 248c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1s23.1 10.75 23.1 23.1S365.3 248 352 248z" fill="currentColor" opacity="0.4"></path>
                    </svg>
                </span>
                <span data-v-4af8cbc5="" class="btn-text-ao-vivo">Ao vivo</span>
            </a>
        </div>
    </div>
</div>

<style>
    /* Esconder menu sports por padrão */
    #divMobileMenuSports {
        display: none;
    }

    /* Responsivo - apenas em mobile (max-width: 768px) */
    @media only screen and (max-width: 768px) {
        /* Na página de esportes em mobile, esconder menu padrão */
        body.sports-page #divMobileMenu {
            display: none !important;
        }

        /* Na página de esportes em mobile, mostrar menu sports */
        body.sports-page #divMobileMenuSports {
            display: block !important;
        }
    }

    /* Desktop (acima de 768px) - garantir que menu sports nunca apareça */
    @media only screen and (min-width: 769px) {
        #divMobileMenuSports {
            display: none !important;
        }
    }

    /* =====================================
       ANIMAÇÃO DE PULSO PARA BADGE LIVE
       ===================================== */
    
    /* Animação de pulso suave para o badge live */
    @keyframes pulse-live {
        0% {
            opacity: 1;
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        }
        50% {
            opacity: 0.9;
            transform: scale(1.15);
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0);
        }
        100% {
            opacity: 1;
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
        }
    }

    /* Aplicar animação no badge live */
    .badge-live.dot {
        animation: pulse-live 1.5s ease-in-out infinite !important;
        display: inline-block !important;
        border-radius: 50% !important;
        position: relative !important;
        opacity: 1 !important;
        visibility: visible !important;
        background-color: #ef4444 !important; 
        height: 4px !important;
        min-width: 4px !important;
        min-height: 4px !important;
        margin-left: 2rem !important; 
        vertical-align: middle !important;
    }

</style>

<!-- Script para alternar os textos e controlar o menu -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Função para verificar se está em dispositivo mobile
        function isMobileDevice() {
            return window.innerWidth <= 768; // Breakpoint para mobile
        }

        // Função para alternar texto
        function toggleText(selector) {
            const element = document.querySelector(selector);
            if (!element) return;

            const originalText = element.textContent;
            const alternateText = element.getAttribute('data-text-alternate');

            setInterval(function() {
                if (element.textContent === originalText) {
                    element.textContent = alternateText;
                } else {
                    element.textContent = originalText;
                }
            }, 3000); // Alterna a cada 3 segundos
        }

        // Iniciar a alternância para os dois elementos
        toggleText('.esportes-text');
        toggleText('.cassino-text');

        // Controle de abertura/fechamento do sidebar menu - APENAS MOBILE
        const sidebarMenu = document.getElementById('divSidebarMenu');
        const menuButton = document.getElementById('btnMobileMenu');
        const closeSidebarBtn = document.getElementById('btnCloseSidebar');
        const mobileMenuContainer = document.getElementById('divMobileMenu');

        // SVGs para alternar no botão do menu
        const menuSVG = `<svg width="26" height="20" viewBox="0 0 26 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 10H21.5" stroke="#8B8FB1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
            <path opacity="0.6" d="M4 4H21.5" stroke="#585B72" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
            <path opacity="0.6" d="M4 16H21.5" stroke="#585B72" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>`;

        const closeSVG = `<svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
            <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z" fill="currentColor"></path>
        </svg>`;

        // Função para atualizar o ícone do botão
        function updateMenuIcon(isOpen) {
            if (menuButton) {
                const iconSpan = menuButton.querySelector('.nuxt-icon');
                if (iconSpan) {
                    iconSpan.innerHTML = isOpen ? closeSVG : menuSVG;
                }
            }
        }

        // Função para abrir o sidebar
        function openSidebar() {
            if (sidebarMenu && isMobileDevice()) {
                sidebarMenu.classList.add('open');
                sidebarMenu.setAttribute('data-isopen', 'true');
                updateMenuIcon(true);
                // Adicionar classe ao body para controlar overflow
                document.body.classList.add('sidebar-open');
            }
        }

        // Função para fechar o sidebar
        function closeSidebar() {
            if (sidebarMenu) {
                sidebarMenu.classList.remove('open');
                sidebarMenu.setAttribute('data-isopen', 'false');
                updateMenuIcon(false);
                // Remover classe do body
                document.body.classList.remove('sidebar-open');
            }
        }

        // Toggle sidebar ao clicar no botão do menu - APENAS MOBILE
        if (menuButton && sidebarMenu) {
            menuButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Só funciona em mobile
                if (!isMobileDevice()) {
                    return;
                }
                
                // Toggle classe 'open' no sidebar
                const isOpen = sidebarMenu.classList.contains('open');
                
                if (isOpen) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }

        // Fechar sidebar ao clicar no botão de fechar
        if (closeSidebarBtn && sidebarMenu) {
            closeSidebarBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeSidebar();
            });
        }

        // Fechar sidebar ao clicar em qualquer link do menu
        if (sidebarMenu) {
            const sidebarLinks = sidebarMenu.querySelectorAll('a');
            sidebarLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (isMobileDevice()) {
                        closeSidebar();
                    }
                });
            });
        }

        // Fechar sidebar ao clicar fora dele - APENAS MOBILE e com seletores mais específicos
        document.addEventListener('click', function(event) {
            if (!isMobileDevice() || !sidebarMenu || !menuButton) {
                return;
            }

            const isClickInsideSidebar = sidebarMenu.contains(event.target);
            const isClickOnMenuButton = menuButton.contains(event.target);
            const isClickOnMobileMenu = mobileMenuContainer && mobileMenuContainer.contains(event.target);
            
            // Excluir cliques em modais, botões de login, depósito, etc.
            const isClickOnModal = event.target.closest('#login-modal-overlay, #login-modal, [onclick*="login-modal"], [onclick*="deposit-modal"], .modal, .dropdown-menu');
            const isClickOnLoginButton = event.target.closest('button[onclick*="login-modal"], a[onclick*="login-modal"]');
            const isClickOnDepositButton = event.target.closest('button[onclick*="deposit-modal"], a[onclick*="deposit-modal"]');
            
            if (!isClickInsideSidebar && 
                !isClickOnMenuButton && 
                !isClickOnMobileMenu &&
                !isClickOnModal &&
                !isClickOnLoginButton &&
                !isClickOnDepositButton &&
                sidebarMenu.classList.contains('open')) {
                closeSidebar();
            }
        });

        // Fechar sidebar quando a tela for redimensionada para desktop
        window.addEventListener('resize', function() {
            if (!isMobileDevice() && sidebarMenu && sidebarMenu.classList.contains('open')) {
                closeSidebar();
            }
        });

        // Garantir que o sidebar inicie fechado
        if (sidebarMenu) {
            sidebarMenu.classList.remove('open');
            sidebarMenu.setAttribute('data-isopen', 'false');
            updateMenuIcon(false);
        }

        // =====================================
        // SEÇÃO: INTEGRAÇÃO COM SISTEMA DE LOGIN
        // =====================================

        // Função para configurar botões de depósito no mobile
        function setupMobileDepositButtons() {
            const mobileDepositButtons = document.querySelectorAll('[data-action="deposit"], button[onclick*="open-deposit-modal"], .btn-deposit');
            
            mobileDepositButtons.forEach(function(btn) {
                if (btn && !btn.hasAttribute('data-mobile-configured')) {
                    btn.setAttribute('data-mobile-configured', 'true');
                    
                    // Remover onclick inline para evitar conflitos
                    btn.removeAttribute('onclick');
                    
                    // Adicionar listener de clique
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        console.log('Botão de depósito mobile clicado');
                        
                        // Verificar se o usuário está logado
                        const isAuthenticated = document.querySelector('.auth-required, .realAmount, #balance_header');
                        
                        if (isAuthenticated) {
                            // Usuário logado - abrir modal de depósito
                            const depositModal = document.getElementById('depositModal');
                            if (depositModal) {
                                depositModal.classList.remove('hidden');
                                depositModal.classList.add('show');

                                // Inicializar valor padrão
                                const depositAmount = document.getElementById('depositAmount');
                                if (depositAmount) {
                                    depositAmount.value = "50,00";
                                }

                                // Inicializar o sistema de bônus
                                if (typeof window.reinitializeBonusSystem === 'function') {
                                    setTimeout(function() {
                                        window.reinitializeBonusSystem();
                                    }, 100);
                                }
                            } else {
                                console.error('Modal de depósito não encontrado');
                            }
                        } else {
                            // Usuário não logado - abrir modal de login
                            const loginOverlay = document.getElementById('login-modal-overlay');
                            const loginModal = document.getElementById('login-modal');
                            
                            if (loginOverlay && loginModal) {
                                loginOverlay.style.display = 'block';
                                loginModal.style.display = 'block';
                            }
                        }
                    });
                }
            });
        }

        // Configurar botões inicialmente
        setupMobileDepositButtons();

        // Listener para eventos de atualização do header (login/logout)
        window.addEventListener('header:updated', function(event) {
            setTimeout(() => {
                // Reconfigurar botões de depósito após mudança de autenticação
                setupMobileDepositButtons();
                
                // Reinicializar LoadJS para modais de depósito se disponível
                if (typeof LoadJS === 'function') {
                    LoadJS();
                }
            }, 250);
        });

        // Listener para eventos específicos de login
        window.addEventListener('auth:loginSuccess', function(event) {
            setTimeout(() => {
                setupMobileDepositButtons();
            }, 250);
        });

        // =====================================
        // SEÇÃO: CONTROLE DO MENU MOBILE DE SPORTS
        // =====================================

        // Buscar o menu mobile específico de sports e o sidebar existente
        const mobileMenuSports = document.getElementById('divMobileMenuSports');
        const btnMobileMenuSports = document.getElementById('mn-mb-01');
        
        // Controle de abertura/fechamento do sidebar via botão do menu sports
        if (btnMobileMenuSports && sidebarMenu) {
            btnMobileMenuSports.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (!isMobileDevice()) return;
                
                // Toggle do sidebar
                const isOpen = sidebarMenu.classList.contains('open');
                
                if (isOpen) {
                    sidebarMenu.classList.remove('open');
                    sidebarMenu.setAttribute('data-isopen', 'false');
                    document.body.classList.remove('sidebar-open');
                } else {
                    sidebarMenu.classList.add('open');
                    sidebarMenu.setAttribute('data-isopen', 'true');
                    document.body.classList.add('sidebar-open');
                }
            });
        }
        
        // Detectar se estamos na página de sports e adicionar classe ao body
        if (window.location.pathname.includes('/sports')) {
            document.body.classList.add('sports-page');
        }

        // =====================================
        // SEÇÃO: ALTERNÂNCIA DE TEXTO "AO VIVO"
        // =====================================
        
        // Função para alternar texto dos botões "Ao vivo"
        function toggleAoVivoText() {
            // Botão Esportes Ao Vivo (mn-mb-03)
            const btnEsportesAoVivo = document.querySelector('#mn-mb-03 .btn-text-ao-vivo');
            if (btnEsportesAoVivo) {
                const btnEsportes = document.getElementById('mn-mb-03');
                const alternateText = btnEsportes ? btnEsportes.getAttribute('data-text-alternate') : 'Esportes';
                const currentText = btnEsportesAoVivo.textContent.trim();
                
                if (currentText === 'Ao vivo') {
                    btnEsportesAoVivo.textContent = alternateText;
                } else {
                    btnEsportesAoVivo.textContent = 'Ao vivo';
                }
            }

            // Botão Cassino Ao Vivo (mn-mb-05)
            const btnCassinoAoVivo = document.querySelector('#mn-mb-05 .btn-text-ao-vivo');
            if (btnCassinoAoVivo) {
                const btnCassino = document.getElementById('mn-mb-05');
                const alternateText = btnCassino ? btnCassino.getAttribute('data-text-alternate') : 'Cassino';
                const currentText = btnCassinoAoVivo.textContent.trim();
                
                if (currentText === 'Ao vivo') {
                    btnCassinoAoVivo.textContent = alternateText;
                } else {
                    btnCassinoAoVivo.textContent = 'Ao vivo';
                }
            }
        }

        // Iniciar alternância a cada 3 segundos
        setInterval(toggleAoVivoText, 3000);
    });
</script>
