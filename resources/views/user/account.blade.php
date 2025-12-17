@extends('layouts.app')
@section('content')
<div class="_2sSj3">
    <div class="is0Ic">
        @include('profile.partials.menu')
<div class="cnynX">
    <div class="fVeX8" data-headerheight="55" data-topbarheight="0" data-v-owner="3076" style="--236d1da4: 55px;">
        <a class="nuxt-icon nuxt-icon--fill pvpfG" href="{{ route('user.wallet') }}">
            <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"
                    fill="currentColor"
                ></path>
            </svg>
        </a>
        <span class="DxKO1">{{ __('menu.account_data_title') }}</span>
        <div class="nu8zQ"></div>
    </div>
    <div class="_6NoZq" data-v-owner="3076" style="--236d1da4: 55px;">
        <!----><!----><!---->
        <div class="flex flex-col gap-4 md:gap-5">
            <!---->
            <div class="_94Q8s">
                <div class="g5YkC">
                    <div class="gvPJB">{{ __('menu.email') }} <span>*</span></div>
                    @if(auth()->user()->email_verified_at)
                    <div class="nZvE6 qzYqb bg-success">
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="currentColor"></path>
                            </svg>
                        </span> 
                        Verificado
                    </div>
                    <button class="cyeNp" id="email-edit-btn">{{ __('menu.edit') }}</button>
                    @else
                    <div class="nZvE6 qzYqb">
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z" fill="currentColor"></path>
                            </svg>
                        </span> 
                        Não verificado
                    </div>
                    <div class="flex gap-2">
                        <button class="cyeNp" id="email-edit-btn-unverified">{{ __('menu.edit') }}</button>
                        <button class="cyeNp email-verify" onclick="carregarModal('email')">Verificar</button>
                    </div>
                    @endif
                </div>
                <div class="ihDn-">
                    {{ auth()->user()->email }} <br />
                    <button class="Yohhm">{{ __('menu.click_to_edit_email') }}</button>
                </div>
                <div class="_94Q8s">
                    
                    <form class="_3hH0a" id="email-edit-form" method="POST" action="{{ route('user.update.email') }}" style="display: none;">
                        @csrf
                        <div class="ybuu0">
                            <div data-v-44b1d268="" class="input-group">
                                <!----><!---->
                                <div data-v-44b1d268="" class="group placeh" disabled="false" for="email">
                                    <input data-v-44b1d268="" id="email" class="peer input hasContent" name="email" placeholder="{{ __('menu.email') }}" type="email" validate-on-blur="true" validate-on-change="true" value="{{ auth()->user()->email }}" />
                                    <!----><!---->
                                </div>
                                <!----><!---->
                            </div>
                            <div id="email-error" class="text-red-500 text-sm" style="display: none;"></div>
                            <div class="mt-2 text-amber-500 text-sm">
                                <span class="nuxt-icon nuxt-icon--fill">
                                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                Atenção: Ao atualizar seu email, será necessário verificá-lo novamente.
                            </div>
                        </div>
                        <div class="flex justify-between items-end w-full col-span-2">
                            <button class="aXpF1" type="submit">
                                <span class="nuxt-icon nuxt-icon--fill">
                                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"
                                            fill="currentColor"
                                        ></path>
                                    </svg>
                                </span>
                                {{ __('menu.edit') }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
            <div class="Mp2Xg"></div>
            <!---->
            <div class="_94Q8s">
                <div class="g5YkC">
                    <div class="gvPJB">{{ __('menu.mobile') }} <span>*</span></div>
                    @if(auth()->user()->phone_verified_at)
                    <div class="nZvE6 qzYqb bg-success">
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="currentColor"></path>
                            </svg>
                        </span> 
                        Verificado
                    </div>
                    <button class="cyeNp" id="phone-edit-btn">{{ __('menu.edit') }}</button>
                    @else
                    <div class="nZvE6 qzYqb">
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z" fill="currentColor"></path>
                            </svg>
                        </span> 
                        Não verificado
                    </div>
                        <button class="cyeNp" id="phone-edit-btn-unverified">{{ __('menu.edit') }}</button>
                    @endif
                </div>
                <div class="ihDn-" id="phone-display" style="cursor: pointer;">
                    {{ preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '+55 ($1) $2-$3', auth()->user()->phone) }}<br />
                    <button class="Yohhm">{{ __('menu.click_to_edit_phone') }}</button>
                </div>
                <div class="_94Q8s">
                    
                    <form class="_3hH0a" id="phone-edit-form" method="POST" action="{{ route('user.update.phone') }}" style="display: none;">
                        @csrf
                        <div class="ybuu0">
                            <div data-v-44b1d268="" class="input-group" autocomplete="off">
                                <div data-v-44b1d268="" class="prefix ddi">
                                    <div class="absolute">
                                        <div class="relative inline-block text-left" data-headlessui-state="">
                                            <button
                                                type="button"
                                                aria-haspopup="listbox"
                                                aria-expanded="false"
                                                class="inline-flex w-full justify-center items-center rounded-none px-4 py-2 text-sm font-medium"
                                            >
                                                <span class="nuxt-icon text-xl mr-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512">
                                                        <mask id="a"><circle cx="256" cy="256" r="256" fill="#fff"></circle></mask>
                                                        <g mask="url(#a)">
                                                            <path fill="#6da544" d="M0 0h512v512H0z"></path>
                                                            <path fill="#ffda44" d="M256 100.2 467.5 256 256 411.8 44.5 256z"></path>
                                                            <path fill="#eee" d="M174.2 221a87 87 0 0 0-7.2 36.3l162 49.8a88.5 88.5 0 0 0 14.4-34c-40.6-65.3-119.7-80.3-169.1-52z"></path>
                                                            <path
                                                                fill="#0052b4"
                                                                d="M255.7 167a89 89 0 0 0-41.9 10.6 89 89 0 0 0-39.6 43.4 181.7 181.7 0 0 1 169.1 52.2 89 89 0 0 0-9-59.4 89 89 0 0 0-78.6-46.8zM212 250.5a149 149 0 0 0-45 6.8 89 89 0 0 0 10.5 40.9 89 89 0 0 0 120.6 36.2 89 89 0 0 0 30.7-27.3A151 151 0 0 0 212 250.5z"
                                                            ></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                                +55
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div data-v-44b1d268="" class="group ddi placeh" disabled="false" for="phone">
                                    <input data-v-44b1d268="" id="phone" class="peer input hasContent" name="phone" placeholder="{{ __('menu.phone') }}" type="text" validate-on-blur="true" validate-on-change="true" value="{{ preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1) $2-$3', auth()->user()->phone) }}" />
                                    <!----><!---->
                                </div>
                                <!----><!---->
                            </div>
                            @error('phone')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            <div id="phone-error" class="text-red-500 text-sm" style="display: none;"></div>
                            <div class="mt-2 text-amber-500 text-sm">
                                <span class="nuxt-icon nuxt-icon--fill">
                                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                Atenção: Ao atualizar seu telefone, será necessário verificá-lo novamente.
                            </div>
                        </div>
                        <div class="flex justify-between items-end w-full col-span-2">
                            <button class="aXpF1" type="submit">
                                <span class="nuxt-icon nuxt-icon--fill">
                                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                {{ __('menu.edit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="Mp2Xg"></div>
            <!---->
            <div class="c4vtO">
                <!---->
                <div class="fneI-">
                    <div class="wbyYO">
                        Endereço
                        <!---->
                    </div>
                    <div class="SNgSG _7dAAu">
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                        Incompleto
                    </div>
                    <button class="_43-Wa" id="address-edit-btn">Editar</button>
                </div>
                <div class="haT02" id="address-display"><span class="text-primary text-xs cursor-pointer">Endereço não informado ou incompleto. Clique aqui para atualizar.</span></div>
                
                <!-- Formulário de edição de endereço - inicialmente oculto -->
                <form class="_2jODJ" id="address-edit-form" method="POST" action="{{ route('user.update.address') }}" style="display: none;">
                    @csrf
                    <div class="_4injO">Aqui você pode conferir e atualizar os detalhes do seu endereço. Todos os campos são obrigatórios para que seu endereço seja considerado completo.</div>
                    <div class="_0u8RX registerFields">
                        <section class="flex gap-5">
                            <div class="flex-grow relative">
                                <label class="registerLabel" for="zipcode">CEP (Código Postal) <span>*</span></label>
                                <div data-v-44b1d268="" class="input-group">
                                    <!----><!---->
                                    <div data-v-44b1d268="" class="group placeh" disabled="false" for="zipcode">
                                        <input data-v-44b1d268="" id="zipcode" class="peer input" name="cep" placeholder="ex: 98765-123" type="tel" validate-on-blur="true" validate-on-change="true" value="{{ auth()->user()->address->cep ?? '' }}" />
                                        <!----><!---->
                                    </div>
                                    <!----><!---->
                                </div>
                                <!---->
                            </div>
                            <div class="flex-grow">
                                <label class="registerLabel" for="userCountry">País <span>*</span></label>
                                <div class="SDY2G undefined">
                                    <div class="VJkZN">
                                        <!---->
                                        <div class="ES7kQ AaCbA">
                                            <div class="v2XVD" data-headlessui-state="disabled">
                                                <button id="userCountry" type="button" aria-haspopup="listbox" aria-expanded="false" disabled="" data-headlessui-state="disabled" class="yUxQy">
                                                    <span class="nuxt-icon hsnsP">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512">
                                                            <mask id="a"><circle cx="256" cy="256" r="256" fill="#fff"></circle></mask>
                                                            <g mask="url(#a)">
                                                                <path fill="#6da544" d="M0 0h512v512H0z"></path>
                                                                <path fill="#ffda44" d="M256 100.2 467.5 256 256 411.8 44.5 256z"></path>
                                                                <path fill="#eee" d="M174.2 221a87 87 0 0 0-7.2 36.3l162 49.8a88.5 88.5 0 0 0 14.4-34c-40.6-65.3-119.7-80.3-169.1-52z"></path>
                                                                <path
                                                                    fill="#0052b4"
                                                                    d="M255.7 167a89 89 0 0 0-41.9 10.6 89 89 0 0 0-39.6 43.4 181.7 181.7 0 0 1 169.1 52.2 89 89 0 0 0-9-59.4 89 89 0 0 0-78.6-46.8zM212 250.5a149 149 0 0 0-45 6.8 89 89 0 0 0 10.5 40.9 89 89 0 0 0 120.6 36.2 89 89 0 0 0 30.7-27.3A151 151 0 0 0 212 250.5z"
                                                                ></path>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                    BRA
                                                    <div class="j-iRm _-6uut">
                                                        <span class="nuxt-icon nuxt-icon--fill">
                                                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"
                                                                    fill="currentColor"
                                                                ></path>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </button>
                                                <!---->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="flex gap-5">
                            <div class="flex-grow">
                                <label class="registerLabel" for="logradouro">Rua <span>*</span></label>
                                <div data-v-44b1d268="" class="input-group">
                                    <!----><!---->
                                    <div data-v-44b1d268="" class="group placeh" disabled="false" for="logradouro">
                                        <input data-v-44b1d268="" id="logradouro" class="peer input" name="logradouro" placeholder="ex: Rua das Flores" type="text" validate-on-blur="true" validate-on-change="true" value="{{ auth()->user()->address->logradouro ?? '' }}" />
                                        <!----><!---->
                                    </div>
                                    <!----><!---->
                                </div>
                            </div>
                            <div class="flex-grow-0" style="width: 120px;">
                                <label class="registerLabel" for="numero">Número <span>*</span></label>
                                <div data-v-44b1d268="" class="input-group">
                                    <!----><!---->
                                    <div data-v-44b1d268="" class="group placeh" disabled="false" for="numero">
                                        <input data-v-44b1d268="" id="numero" class="peer input" name="numero" placeholder="ex: 567" type="text" validate-on-blur="true" validate-on-change="true" value="{{ auth()->user()->address->numero ?? '' }}" />
                                        <!----><!---->
                                    </div>
                                    <!----><!---->
                                </div>
                            </div>
                        </section>
                        <section class="flex gap-5">
                            <div class="flex-grow">
                                <label class="registerLabel" for="bairro">Bairro <span>*</span></label>
                                <div data-v-44b1d268="" class="input-group">
                                    <!----><!---->
                                    <div data-v-44b1d268="" class="group placeh" disabled="false" for="bairro">
                                        <input data-v-44b1d268="" id="bairro" class="peer input" name="bairro" placeholder="ex: Centro" type="text" validate-on-blur="true" validate-on-change="true" value="{{ auth()->user()->address->bairro ?? '' }}" />
                                        <!----><!---->
                                    </div>
                                    <!----><!---->
                                </div>
                            </div>
                            <div class="flex-grow">
                                <label class="registerLabel" for="complemento">Complemento</label>
                                <div data-v-44b1d268="" class="input-group">
                                    <!----><!---->
                                    <div data-v-44b1d268="" class="group placeh" disabled="false" for="complemento">
                                        <input data-v-44b1d268="" id="complemento" class="peer input" name="complemento" placeholder="ex: Ap 1202" type="text" validate-on-blur="true" validate-on-change="true" value="{{ auth()->user()->address->complemento ?? '' }}" />
                                        <!----><!---->
                                    </div>
                                    <!----><!---->
                                </div>
                            </div>
                        </section>
                        <section class="flex gap-5">
                            <div class="flex-grow">
                                <label class="registerLabel" for="cidade">Cidade <span>*</span></label>
                                <div data-v-44b1d268="" class="input-group">
                                    <!----><!---->
                                    <div data-v-44b1d268="" class="group placeh" disabled="false" for="cidade">
                                        <input data-v-44b1d268="" id="cidade" class="peer input" name="cidade" placeholder="ex: Belo Horizonte" type="text" validate-on-blur="true" validate-on-change="true" value="{{ auth()->user()->address->cidade ?? '' }}" />
                                        <!----><!---->
                                    </div>
                                    <!----><!---->
                                </div>
                            </div>
                            <div class="flex-grow">
                                <label class="registerLabel" for="estado">Estado <span>*</span></label>
                                <div data-v-44b1d268="" class="input-group">
                                    <!----><!---->
                                    <div data-v-44b1d268="" class="group placeh" disabled="false" for="estado">
                                        <select data-v-44b1d268="" id="estado" class="peer input" name="estado" validate-on-blur="true" validate-on-change="true">
                                            <option data-v-44b1d268="" value="0">Selecione</option>
                                            <option data-v-44b1d268="" value="AC" {{ (auth()->user()->address->estado ?? '') == 'AC' ? 'selected' : '' }}>Acre (AC)</option>
                                            <option data-v-44b1d268="" value="AL" {{ (auth()->user()->address->estado ?? '') == 'AL' ? 'selected' : '' }}>Alagoas (AL)</option>
                                            <option data-v-44b1d268="" value="AP" {{ (auth()->user()->address->estado ?? '') == 'AP' ? 'selected' : '' }}>Amapá (AP)</option>
                                            <option data-v-44b1d268="" value="AM" {{ (auth()->user()->address->estado ?? '') == 'AM' ? 'selected' : '' }}>Amazonas (AM)</option>
                                            <option data-v-44b1d268="" value="BA" {{ (auth()->user()->address->estado ?? '') == 'BA' ? 'selected' : '' }}>Bahia (BA)</option>
                                            <option data-v-44b1d268="" value="CE" {{ (auth()->user()->address->estado ?? '') == 'CE' ? 'selected' : '' }}>Ceará (CE)</option>
                                            <option data-v-44b1d268="" value="DF" {{ (auth()->user()->address->estado ?? '') == 'DF' ? 'selected' : '' }}>Distrito Federal (DF)</option>
                                            <option data-v-44b1d268="" value="ES" {{ (auth()->user()->address->estado ?? '') == 'ES' ? 'selected' : '' }}>Espírito Santo (ES)</option>
                                            <option data-v-44b1d268="" value="GO" {{ (auth()->user()->address->estado ?? '') == 'GO' ? 'selected' : '' }}>Goiás (GO)</option>
                                            <option data-v-44b1d268="" value="MA" {{ (auth()->user()->address->estado ?? '') == 'MA' ? 'selected' : '' }}>Maranhão (MA)</option>
                                            <option data-v-44b1d268="" value="MT" {{ (auth()->user()->address->estado ?? '') == 'MT' ? 'selected' : '' }}>Mato Grosso (MT)</option>
                                            <option data-v-44b1d268="" value="MS" {{ (auth()->user()->address->estado ?? '') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul (MS)</option>
                                            <option data-v-44b1d268="" value="MG" {{ (auth()->user()->address->estado ?? '') == 'MG' ? 'selected' : '' }}>Minas Gerais (MG)</option>
                                            <option data-v-44b1d268="" value="PA" {{ (auth()->user()->address->estado ?? '') == 'PA' ? 'selected' : '' }}>Pará (PA)</option>
                                            <option data-v-44b1d268="" value="PB" {{ (auth()->user()->address->estado ?? '') == 'PB' ? 'selected' : '' }}>Paraíba (PB)</option>
                                            <option data-v-44b1d268="" value="PR" {{ (auth()->user()->address->estado ?? '') == 'PR' ? 'selected' : '' }}>Paraná (PR)</option>
                                            <option data-v-44b1d268="" value="PE" {{ (auth()->user()->address->estado ?? '') == 'PE' ? 'selected' : '' }}>Pernambuco (PE)</option>
                                            <option data-v-44b1d268="" value="PI" {{ (auth()->user()->address->estado ?? '') == 'PI' ? 'selected' : '' }}>Piauí (PI)</option>
                                            <option data-v-44b1d268="" value="RJ" {{ (auth()->user()->address->estado ?? '') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro (RJ)</option>
                                            <option data-v-44b1d268="" value="RN" {{ (auth()->user()->address->estado ?? '') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte (RN)</option>
                                            <option data-v-44b1d268="" value="RS" {{ (auth()->user()->address->estado ?? '') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul (RS)</option>
                                            <option data-v-44b1d268="" value="RO" {{ (auth()->user()->address->estado ?? '') == 'RO' ? 'selected' : '' }}>Rondônia (RO)</option>
                                            <option data-v-44b1d268="" value="RR" {{ (auth()->user()->address->estado ?? '') == 'RR' ? 'selected' : '' }}>Roraima (RR)</option>
                                            <option data-v-44b1d268="" value="SC" {{ (auth()->user()->address->estado ?? '') == 'SC' ? 'selected' : '' }}>Santa Catarina (SC)</option>
                                            <option data-v-44b1d268="" value="SP" {{ (auth()->user()->address->estado ?? '') == 'SP' ? 'selected' : '' }}>São Paulo (SP)</option>
                                            <option data-v-44b1d268="" value="SE" {{ (auth()->user()->address->estado ?? '') == 'SE' ? 'selected' : '' }}>Sergipe (SE)</option>
                                            <option data-v-44b1d268="" value="TO" {{ (auth()->user()->address->estado ?? '') == 'TO' ? 'selected' : '' }}>Tocantins (TO)</option>
                                        </select>
                                    </div>
                                    <!----><!---->
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="flex justify-between items-end w-full col-span-2">
                        <div>
                            <button class="Nc77k" type="submit" id="save-address-btn">
                                <span class="nuxt-icon nuxt-icon--fill">
                                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="Mp2Xg"></div>
            <!---->
            <div class="CN76k">
                <div class="_3bOgY">
                    <div class="ALOe4">Meu Pix</div>
                </div>
                <div class="FErgT">
                    <div class="inline-flex items-center gap-2">
                        <span class="nuxt-icon inline-flex">
                            <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 315.4 315.6">
                                <path
                                    d="M245.9,241.3c-12.3,0-24.1-4.8-32.8-13.5l-47.4-47.4c-3.5-3.3-9-3.3-12.4,0l-47.5,47.5c-8.7,8.7-20.5,13.6-32.8,13.6h-9.3l60,60c18.7,18.7,49.1,18.7,67.8,0l60.1-60.1h-5.8.1ZM73.1,73.9c12.3,0,24.1,4.9,32.8,13.6l47.5,47.5c3.4,3.4,9,3.4,12.4,0l47.3-47.3c8.7-8.7,20.5-13.6,32.8-13.6h5.7l-60.1-60.1c-18.7-18.7-49.1-18.7-67.8,0h0l-59.9,59.9h9.3ZM301.4,123.8l-36.3-36.3c-.8.3-1.7.5-2.6.5h-16.5c-8.6,0-16.8,3.4-22.9,9.5l-47.3,47.3c-8.9,8.9-23.3,8.9-32.1,0l-47.5-47.5c-6.1-6.1-14.3-9.5-22.9-9.5h-20.3c-.8,0-1.7-.2-2.4-.5L14,123.8c-18.7,18.7-18.7,49.1,0,67.8l36.5,36.5c.8-.3,1.6-.5,2.4-.5h20.4c8.6,0,16.8-3.4,22.9-9.5l47.5-47.5c8.6-8.6,23.6-8.6,32.1,0l47.3,47.3c6.1,6.1,14.3,9.5,22.9,9.5h16.5c.9,0,1.8.2,2.6.5l36.3-36.3c18.7-18.7,18.7-49.1,0-67.8h0"
                                    style="fill: #32bcad; stroke-width: 0px;"
                                ></path>
                            </svg>
                        </span>
                        CPF: {{ auth()->user()->pix }}
                    </div>
                    @if(auth()->user()->nascimento)
                    <div class="mt-2">
                        <span class="text-sm">Data de Nascimento: 
                            @php
                                try {
                                    echo \Carbon\Carbon::parse(auth()->user()->nascimento)->format('d/m/Y');
                                } catch (\Exception $e) {
                                    try {
                                        echo \Carbon\Carbon::createFromFormat('d/m/Y', auth()->user()->nascimento)->format('d/m/Y');
                                    } catch (\Exception $e) {
                                        echo auth()->user()->nascimento;
                                    }
                                }
                            @endphp
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="Mp2Xg"></div>
            <!---->
            <div class="_1Atxy">
                <div class="Vy54Q">
                    <div class="L4UGD">Dados da conta</div>
                </div>
                <div class="mbaKx">
                    <div class="flex gap-4 uppercase">
                        <div class="inline-flex items-center gap-2">
                            <span class="nuxt-icon inline-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512">
                                    <mask id="a"><circle cx="256" cy="256" r="256" fill="#fff"></circle></mask>
                                    <g mask="url(#a)">
                                        <path fill="#6da544" d="M0 0h512v512H0z"></path>
                                        <path fill="#ffda44" d="M256 100.2 467.5 256 256 411.8 44.5 256z"></path>
                                        <path fill="#eee" d="M174.2 221a87 87 0 0 0-7.2 36.3l162 49.8a88.5 88.5 0 0 0 14.4-34c-40.6-65.3-119.7-80.3-169.1-52z"></path>
                                        <path
                                            fill="#0052b4"
                                            d="M255.7 167a89 89 0 0 0-41.9 10.6 89 89 0 0 0-39.6 43.4 181.7 181.7 0 0 1 169.1 52.2 89 89 0 0 0-9-59.4 89 89 0 0 0-78.6-46.8zM212 250.5a149 149 0 0 0-45 6.8 89 89 0 0 0 10.5 40.9 89 89 0 0 0 120.6 36.2 89 89 0 0 0 30.7-27.3A151 151 0 0 0 212 250.5z"
                                        ></path>
                                    </g>
                                </svg>
                            </span>
                            BRA
                        </div>
                        <span class="opacity-30"> | </span> BRL <span class="opacity-30"> | </span> pt-br
                    </div>
                </div>
            </div>
    </div>
</div>
</div>
</div>

<!-- Incluir modais -->
<div id="modal-container" style="display: none;">
    <!-- O conteúdo do modal será carregado dinamicamente aqui -->
</div>

<!-- Templates dos modais pré-carregados (inicialmente ocultos) -->
<div id="email-modal-template" style="display: none;">
    @include('user.modals.email')
</div>


<style>
    .btn-cancel {
        background-color: #6c757d;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .btn-cancel:hover {
        background-color: #5a6268;
    }
    .c4vtO ._2jODJ .Nc77k{
        color:  var(--text-btn-primary);
    }
    
    #estado option {
        background-color: #424344 !important;
    }
    
    /* Adicionar estilo para o indicador de loading */
    .loading {
        position: relative;
    }
    
    .loading::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }
    
    .spinner-border {
        display: inline-block;
        width: 1em;
        height: 1em;
        border: 0.2em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border .75s linear infinite;
        vertical-align: -0.125em;
        margin-right: 0.5em;
    }
    
    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }
</style>
<script>
    // Remover o script relacionado aos botões de teste
document.addEventListener('DOMContentLoaded', function() {
        // Função para exibir mensagens de sucesso que ainda é necessária
        window.mostrarMensagemSucesso = function(mensagem) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'status-popup status-popup-success';
        notification.innerHTML = `
            <div class="status-icon status-icon-success">
                <i class="fa fa-check"></i>
            </div>
            <div class="status-message">${mensagem}</div>
            <div class="status-close">&times;</div>
            <div class="status-progress-success"></div>
        `;
        
        // Add to DOM
        document.body.appendChild(notification);
        
        // Set up close button
        const closeBtn = notification.querySelector('.status-close');
        closeBtn.addEventListener('click', function() {
            document.body.removeChild(notification);
        });
        
        // Auto remove after animation completes
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 3000);
        };
    
        // Função para exibir mensagens de erro que ainda é necessária
        window.mostrarMensagemErro = function(mensagem) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'status-popup status-popup-error';
        notification.innerHTML = `
            <div class="status-icon status-icon-error">
                <i class="fa fa-times"></i>
            </div>
            <div class="status-message">${mensagem}</div>
            <div class="status-close">&times;</div>
            <div class="status-progress-error"></div>
        `;
        
        // Add to DOM
        document.body.appendChild(notification);
        
        // Set up close button
        const closeBtn = notification.querySelector('.status-close');
        closeBtn.addEventListener('click', function() {
            document.body.removeChild(notification);
        });
        
        // Auto remove after animation completes
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 3000);
        };
        
        // Adicionar listeners para botões de edição
        // Email edit button - Corrigindo o seletor
        const emailEditBtn = document.getElementById('email-edit-btn');
        const emailEditBtnUnverified = document.getElementById('email-edit-btn-unverified');
        const emailDisplay = document.querySelector('.ihDn-');
        const emailForm = document.getElementById('email-edit-form');
        
        // Função para alternar edição de email
        function toggleEmailEdit(button) {
            if (emailForm.style.display === 'none' || emailForm.style.display === '') {
                emailDisplay.style.display = 'none';
                emailForm.style.display = 'block';
                document.getElementById('email').focus();
                button.textContent = 'Cancelar';
            } else {
                emailDisplay.style.display = 'block';
                emailForm.style.display = 'none';
                button.textContent = '{{ __('menu.edit') }}';
            }
        }
        
        if (emailEditBtn && emailDisplay && emailForm) {
            emailEditBtn.addEventListener('click', function() {
                toggleEmailEdit(this);
            });
            
            emailDisplay.addEventListener('click', function() {
                emailDisplay.style.display = 'none';
                emailForm.style.display = 'block';
                document.getElementById('email').focus();
                if (emailEditBtn) emailEditBtn.textContent = 'Cancelar';
            });
        }
        
        if (emailEditBtnUnverified && emailDisplay && emailForm) {
            emailEditBtnUnverified.addEventListener('click', function() {
                toggleEmailEdit(this);
            });
        }
        
        // Phone edit button
        const phoneEditBtn = document.getElementById('phone-edit-btn');
        const phoneEditBtnUnverified = document.getElementById('phone-edit-btn-unverified');
        const phoneDisplay = document.getElementById('phone-display');
        const phoneForm = document.getElementById('phone-edit-form');
        
        // Função para alternar edição de telefone
        function togglePhoneEdit(button) {
            if (phoneForm.style.display === 'none' || phoneForm.style.display === '') {
                phoneDisplay.style.display = 'none';
                phoneForm.style.display = 'block';
                document.getElementById('phone').focus();
                button.textContent = 'Cancelar';
            } else {
                phoneDisplay.style.display = 'block';
                phoneForm.style.display = 'none';
                button.textContent = '{{ __('menu.edit') }}';
            }
        }
        
        if (phoneEditBtn && phoneDisplay && phoneForm) {
            phoneEditBtn.addEventListener('click', function() {
                togglePhoneEdit(this);
            });
            
            phoneDisplay.addEventListener('click', function() {
                phoneDisplay.style.display = 'none';
                phoneForm.style.display = 'block';
                document.getElementById('phone').focus();
                if (phoneEditBtn) phoneEditBtn.textContent = 'Cancelar';
            });
        }
        
        if (phoneEditBtnUnverified && phoneDisplay && phoneForm) {
            phoneEditBtnUnverified.addEventListener('click', function() {
                togglePhoneEdit(this);
            });
        }
        
        // Address edit button
        const editAddressBtn = document.getElementById('address-edit-btn');
        const displayAddress = document.getElementById('address-display');
        const addressForm = document.getElementById('address-edit-form');
        
        if (editAddressBtn && displayAddress && addressForm) {
            // Verificar se o endereço está completo e atualizar a exibição
            function checkAndDisplayAddress() {
                // Verificar se existem elementos no formulário
                const cep = document.getElementById('zipcode')?.value;
                const logradouro = document.getElementById('logradouro')?.value;
                const numero = document.getElementById('numero')?.value;
                const complemento = document.getElementById('complemento')?.value;
                const bairro = document.getElementById('bairro')?.value;
                const cidade = document.getElementById('cidade')?.value;
                const estado = document.getElementById('estado')?.value;
                
                // Verificar se endereço está completo
                const isAddressComplete = cep && logradouro && numero && bairro && cidade && estado && estado !== '0';
                
                if (isAddressComplete) {
                    // Formatar endereço para exibição
                    let formattedAddress = `${logradouro}, ${numero}`;
                    if (complemento) {
                        formattedAddress += `, ${complemento}`;
                    }
                    formattedAddress += ` - ${bairro}, ${cidade}/${estado}, CEP: ${cep}`;
                    
                    // Atualizar a exibição do endereço
                    displayAddress.innerHTML = `<span class="text-primary text-xs">${formattedAddress}</span>`;
                    
                    // Atualizar badge para "Completo"
                    const addressBadge = document.querySelector('.SNgSG._7dAAu');
                    if (addressBadge) {
                        addressBadge.className = 'SNgSG _7dAAu bg-success';
                        addressBadge.innerHTML = `
                            <span class="nuxt-icon nuxt-icon--fill">
                                <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="currentColor"></path>
                                </svg>
                            </span> 
                            Completo
                        `;
                    }
                }
            }
            
            // Executar verificação ao carregar
            checkAndDisplayAddress();
            
            editAddressBtn.addEventListener('click', function() {
                if (addressForm.style.display === 'none' || addressForm.style.display === '') {
                    displayAddress.style.display = 'none';
                    addressForm.style.display = 'block';
                    this.textContent = 'Cancelar';
                } else {
                    displayAddress.style.display = 'block';
                    addressForm.style.display = 'none';
                    this.textContent = 'Editar';
                }
            });
            
            displayAddress.addEventListener('click', function() {
                displayAddress.style.display = 'none';
                addressForm.style.display = 'block';
                if (editAddressBtn) editAddressBtn.textContent = 'Cancelar';
            });
        }
        
        // Auto preenchimento de endereço com ViaCEP
        const cepInput = document.getElementById('zipcode');
        if (cepInput) {
            cepInput.addEventListener('blur', function() {
                const cep = this.value.replace(/\D/g, '');
                
                if (cep.length !== 8) {
                    return;
                }
                
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('logradouro').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('estado').value = data.uf;
                            
                            // Focar no campo de número, que não é preenchido automaticamente
                            document.getElementById('numero').focus();
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao consultar o CEP:', error);
                    });
            });
        }
        
        // Adicionar listeners para os formulários
        // Formulário de email
        if (emailForm) {
            emailForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                // Mostrar indicador de carregamento
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...';
                submitBtn.disabled = true;
                
                fetch(this.getAttribute('action'), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarMensagemSucesso(data.message || 'Email atualizado com sucesso!');
                        
                        // Atualizar email na tela
                        const newEmail = document.getElementById('email').value;
                        emailDisplay.innerHTML = `${newEmail}<br><button class="Yohhm">{{ __('menu.click_to_edit_email') }}</button>`;
                        
                        // Esconder formulário e mostrar display
                        emailForm.style.display = 'none';
                        emailDisplay.style.display = 'block';
                        
                        // Atualizar texto do botão
                        if (emailEditBtn) emailEditBtn.textContent = '{{ __('menu.edit') }}';
                        
                        // Abrir modal de verificação após um pequeno delay
                        setTimeout(() => {
                            carregarModal('email');
                        }, 1000);
                    } else {
                        mostrarMensagemErro(data.message || 'Erro ao atualizar email.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao atualizar email:', error);
                    mostrarMensagemErro('Ocorreu um erro ao atualizar o email.');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                });
            });
        }
        
        // Formulário de telefone
        if (phoneForm) {
            phoneForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                // Mostrar indicador de carregamento
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...';
                submitBtn.disabled = true;
                
                fetch(this.getAttribute('action'), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarMensagemSucesso(data.message || 'Telefone atualizado com sucesso!');
                        
                        // Atualizar telefone na tela
                        const newPhone = document.getElementById('phone').value;
                        phoneDisplay.innerHTML = `${newPhone}<br><button class="Yohhm">{{ __('menu.click_to_edit_phone') }}</button>`;
                        
                        // Esconder formulário e mostrar display
                        phoneForm.style.display = 'none';
                        phoneDisplay.style.display = 'block';
                        
                        // Atualizar texto do botão
                        if (phoneEditBtn) phoneEditBtn.textContent = '{{ __('menu.edit') }}';
                        
                    } else {
                        mostrarMensagemErro(data.message || 'Erro ao atualizar telefone.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao atualizar telefone:', error);
                    mostrarMensagemErro('Ocorreu um erro ao atualizar o telefone.');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                });
            });
        }
        
        // Formulário de endereço
        if (addressForm) {
            addressForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                // Mostrar indicador de carregamento
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...';
                submitBtn.disabled = true;
                
                fetch(this.getAttribute('action'), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarMensagemSucesso(data.message || 'Endereço atualizado com sucesso!');
                        
                        // Atualizar a exibição do endereço com os dados retornados
                        if (data.formatted_address) {
                            displayAddress.innerHTML = `<span class="text-primary text-xs">${data.formatted_address}</span>`;
                        } else {
                            // Fallback: chamar a função de verificação para atualizar a exibição
                            checkAndDisplayAddress();
                        }
                        
                        // Atualizar o status de endereço se estiver completo
                        if (data.address_complete) {
                            const addressBadge = document.querySelector('.SNgSG._7dAAu');
                            if (addressBadge) {
                                addressBadge.className = 'SNgSG _7dAAu bg-success';
                                addressBadge.innerHTML = `
                                    <span class="nuxt-icon nuxt-icon--fill">
                                        <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="currentColor"></path>
                                        </svg>
                                    </span> 
                                    Completo
                                `;
                            }
                        }
                        
                        // Esconder formulário e mostrar display
                        addressForm.style.display = 'none';
                        displayAddress.style.display = 'block';
                        
                        // Atualizar texto do botão
                        if (editAddressBtn) editAddressBtn.textContent = 'Editar';
                        
                    } else {
                        mostrarMensagemErro(data.message || 'Erro ao atualizar endereço.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao atualizar endereço:', error);
                    mostrarMensagemErro('Ocorreu um erro ao atualizar o endereço.');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                });
            });
        }
    });

    // A função carregarModal será carregada do arquivo account-edit.js
</script>

<!-- Incluir o arquivo JavaScript para funcionalidades de edição de conta -->
<script src="{{ asset('js/account-edit.js') }}"></script>

@endsection