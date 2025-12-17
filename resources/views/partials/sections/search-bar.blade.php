<div data-v-3c7962a2="" class="gameSearchBar">
            <div data-v-3c7962a2="">
                <div class="UTJUl">
                <span class="inove-icon inove-icon--fill text-texts">
                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M500.3 443.7l-119.7-119.7c-15.03 22.3-34.26 41.54-56.57 56.57l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7z" fill="currentColor"></path>
                        <path
                            d="M207.1 0C93.12 0-.0002 93.13-.0002 208S93.12 416 207.1 416s208-93.13 208-208S322.9 0 207.1 0zM207.1 336c-70.58 0-128-57.42-128-128c0-70.58 57.42-128 128-128s128 57.42 128 128C335.1 278.6 278.6 336 207.1 336z"
                            fill="currentColor"
                            opacity="0.4"
                        ></path>
                    </svg>
                </span>
                    <input
                        id="search-input"
                        class="_6z1L2"
                        name="nope-game-search"
                        placeholder="{{ __('menu.search_casino_game') }}"
                        autocomplete="nope"
                        type="search"
                        data-lpignore="true"
                        aria-autocomplete="none"
                        readonly
                        onfocus="this.removeAttribute('readonly');"
                    />
                </div>
            </div>
            <!-- Adicione o dropdown de resultados aqui -->
            <div data-v-ae76a9e7="" data-v-3c7962a2="" class="searchResults" id="search-results-dropdown" style="display: none; padding: 5px;">
                <!---->
                <div data-v-ae76a9e7="" class="filterWrapper">
                    <button data-v-ae76a9e7="" class="btn categoryActive" data-category="all" onclick="filterByCategory('all')"><span data-v-ae76a9e7="">Todos</span></button>
                    @foreach($cachedData['categories_cache'] as $category)
                        @if(!empty($category))
                            <button data-v-ae76a9e7="" class="btn" data-category="{{ $category }}" onclick="filterByCategory('{{ $category }}')">
                                <span data-v-ae76a9e7="">{{ ucfirst($category) }}</span>
                            </button>
                        @endif
                    @endforeach
                </div>
                <div data-v-ae76a9e7="" class="resultsWrapper">
                    <div data-v-ae76a9e7="" class="nM44t" id="search-grid-container" style="--d879e6ea: 18px; --45b10934: 6;">
                        <div class="tXgOm" id="search-results-container">
                            <!-- Os resultados da pesquisa serão inseridos aqui via JavaScript -->
                        </div>
                    </div>
                </div>
                <div data-v-14a18591="" data-v-ae76a9e7="" class="pagination pt-5 mb-4" id="search-pagination" style="display: none;">
                    <div data-v-14a18591="" class="w-[40%] lg:w-[16%] bg-texts/10 rounded-md h-1 mb-4">
                        <div data-v-14a18591="" class="bg-primary h-1 rounded-md" id="search-progress-bar" style="width: 0%;"></div>
                    </div>
                    <span data-v-14a18591="" class="text-texts text-xs mb-4" id="search-showing-text">{{ __('menu.showing_games', ['current' => 0, 'total' => 0]) }}</span>
                    <button data-v-14a18591="" class="btn-more" id="search-load-more-btn" data-page="1"><span data-v-14a18591="">{{ __('menu.load_more') }}</span></button>
                </div>
            </div>
        </div>