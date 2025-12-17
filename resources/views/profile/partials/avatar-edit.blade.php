@if (Auth::user())
    <!-- Adicione o modal de seleção de avatar no final do arquivo -->
    <div id="avatarModal" class="avatar-modal">
        <div class="avatar-modal-content">
            <span class="avatar-close" id="closeAvatarModal">&times;</span>
            <h2 class="avatar-title">{{ __('messages.select_avatar') }}</h2>

            <div class="avatar-container">
                <!-- Seção de Avatares Padrão -->
                <div class="avatar-section">
                    <h3 class="avatar-section-title">{{ __('messages.default_avatars') }}</h3>
                    <div id="avatar-normal" class="avatar-grid">
                    </div>
                </div>

                <!-- Seção de Avatares Premium Prata -->
                <div class="avatar-section">
                    <h3 class="avatar-section-title">{{ __('messages.silver_premium_avatars') }}</h3>
                    @php
                        $silverRank = Auth::user() ? Auth::user()->getRanking()['level'] >= 4 : false;
                    @endphp

                    @if(!$silverRank)
                        <div class="avatar-message">
                            {{ __('messages.available_silver_rank') }}
                        </div>
                    @endif

                    <div id="avatar-silver" class="avatar-grid {{ !$silverRank ? 'avatar-locked' : '' }}">
                    </div>
                </div>

                <!-- Seção de Avatares Premium Ouro -->
                <div class="avatar-section">
                    <h3 class="avatar-section-title">{{ __('messages.gold_premium_avatars') }}</h3>
                    @php
                        $goldRank = Auth::user() ? Auth::user()->getRanking()['level'] >= 7 : false;
                    @endphp

                    @if(!$goldRank)
                        <div class="avatar-message">
                            Disponível no ranking Ouro ou superior
                        </div>
                    @endif

                    <div id="avatar-gold" class="avatar-grid {{ !$goldRank ? 'avatar-locked' : '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const avatars_normal = [
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/33a2e1ee-4d22-407c-d3c2-d8901c18a200/public",//01
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/ef26adfe-233a-4c32-f575-65e6bf74b100/public",//02
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/bbfc2563-ad74-4405-f2f5-6eda68fbf800/public",//03
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/b4e85acb-58bb-484d-8283-7604ea953b00/public",//04
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/5d5c3270-be48-4077-bafb-33fb0d6dc700/public",//05
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/1fe79105-fac9-4059-b850-17f1a665c100/public",//06
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/749490dc-a830-44f3-e977-d644e351fc00/public",//07
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/ed19f9df-755b-49ca-5bbe-256aee05f300/public",//08
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/44743c2a-8110-4eed-e711-58fb4293c700/public",//09
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/b5e4d099-2040-4f95-6331-4aea31991300/public",//10
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/d5321161-e167-48ef-7585-2ebd77e7d100/public",//11
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/b55c2f75-d964-460e-f0b8-5c5228932300/public",//12
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/d4da447b-e9e5-4aec-c634-9116b9ed2600/public",//13
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/3bca4b98-436d-4ef7-4113-abb7554f7700/public",//14
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/7b549998-6604-4594-9397-f4b81784e000/public",//15
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/716a4533-0c57-4271-2de6-a1f1f639d100/public",//16
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/c9f3c346-a7b3-414b-631f-40a59a3d5500/public",//17
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/7940527d-a0bf-4128-c6a5-cb6dc4abcc00/public",//18
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/d1040f26-f1a0-4891-faf1-e91574814200/public",//19
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/cde08362-e1cf-42f7-9e5b-cfafa3bea100/public",//20
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/b552e61f-c9d3-4a70-c346-6aa563682e00/public",//21
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/4efec635-7af7-44de-04d0-73cac9703200/public",//22
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/e8479347-18ae-41da-f591-6d309089c000/public",//23
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/04e4cd47-408d-4116-9fdb-a4a85bea4800/public",//24
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/c0841dc8-2195-4975-7cac-2fbea6800400/public",//25
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/122f0577-4e25-47fb-0bcd-4f2d7db1b600/public",//26
        ];

        const grid_normal = document.getElementById('avatar-normal');

        avatars_normal.forEach((url, index) => {
            const div = document.createElement('div');
            div.className = 'avatar-option';
            div.setAttribute('data-avatar', url);
            div.setAttribute('data-type', "normal");

            const img = document.createElement('img');
            img.src = url;
            img.alt = `Avatar ${index + 1}`;

            div.appendChild(img);
            grid_normal.appendChild(div);
        });

        const avatars_silver = [
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/c96d9a88-80ff-4dd5-2c47-a8917b818c00/public",//01
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/c2592323-1b32-4427-f727-7e309953e800/public",//02
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/ed1b5049-0ca7-4d46-22c4-f0a8a5a2c200/public",//03
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/18a258c5-beb9-4642-530e-245f56b57f00/public",//04
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/cfc8d3ed-d7bb-4245-f9a5-da7c3adc1500/public",//05
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/590e7701-75c7-4659-f038-906c60800900/public",//06
        ];

        const grid_silver = document.getElementById('avatar-silver');

        avatars_silver.forEach((url, index) => {
            const div = document.createElement('div');
            div.className = 'avatar-option';
            div.setAttribute('data-avatar', url);
            div.setAttribute('data-type', "silver");

            const img = document.createElement('img');
            img.src = url;
            img.alt = `Avatar A-${index + 1}`;

            div.appendChild(img);
            grid_silver.appendChild(div);
        });

        const avatars_gold = [
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/76be8d52-7215-43e1-0781-e9a72df8cc00/public",//01
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/66203681-be0d-4a05-1706-36091d7eb400/public",//02
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/bbc080f8-c5c1-4e81-befb-58baf8563100/public",//03
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/778e2b00-38f2-497d-89d7-e36ff80bbd00/public",//04
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/30870b0d-6686-4a1c-e821-74c2de43d200/public",//05
            "https://imagedelivery.net/TRZwFH1F_vjFyWgjrLgXRQ/3dea6c26-7ba4-47c4-77eb-cb94b4959d00/public",//06
        ];

        const grid_gold = document.getElementById('avatar-gold');

        avatars_gold.forEach((url, index) => {
            const div = document.createElement('div');
            div.className = 'avatar-option';
            div.setAttribute('data-avatar', url);
            div.setAttribute('data-type', "gold");

            const img = document.createElement('img');
            img.src = url;
            img.alt = `Avatar B-${index + 1}`;

            div.appendChild(img);
            grid_gold.appendChild(div);
        });
    </script>
@endif
