<div class="modal fade modal-xl" id="tabsModal" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tabsModalLabel">Gerenciar Agente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="simple-pill">
                    <div id="return_agents"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light-dark" data-bs-dismiss="modal">Cancelar</button>

                @if (auth()->user()->is_admin == 1)
                    <button type="button" class="btn btn-primary" onclick="SaveAgent();">Salvar</button>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .mt-3 {
        margin-top: .75rem;
    }
    .mb-5 {
        margin-bottom: 2.0rem !important;
    }
    .pt-0 {
        padding-top: 0;
    }

    .flex-col {
        flex-direction: column;
    }
    .flex {
        display: flex
    ;
    }

    .bg-white {
        --tw-bg-opacity: 1;
        background-color: rgb(255 255 255 / var(--tw-bg-opacity));
    }
    .rounded-md {
        border-radius: .375rem;
    }
    .mb-4 {
        margin-bottom: 1rem;
    }
    .p-2 {
        padding: .5rem;
    }
    .m-\[auto\], .m-auto {
        margin: auto;
    }

    .text-muted-200 {
        --tw-text-opacity: 1;
        color: rgb(var(--color-muted-200) / var(--tw-text-opacity));
    }
    .font-medium {
        font-weight: 500;
    }
    .pb-4 {
        padding-bottom: 1rem;
    }

    blockquote, dd, dl, figure, h1, h2, h3, h4, h5, h6, hr, p, pre {
        margin: 0;
    }
    h1, h2, h3, h4, h5, h6 {
        font-size: inherit;
        font-weight: inherit;
    }

    body.dark .card .card-body {
        padding: 24px 20px 10px; !important;
    }

    .arredonda {
        vertical-align: middle; !important;
    }
</style>

<div class="modal fade modal-xl" id="categoriesModal" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tabsModalLabel">Gerenciar Menu Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="simple-pill">
                    <div id="return_categoria"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light-dark" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="SaveCategoriesItems();">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-xl" id="newmenuitem" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tabsModalLabel">Novo Item do Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="simple-pill">
                    <div id="return_newmenuitem"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light-dark" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="AddCategoriesItems();">Salvar</button>
            </div>
        </div>
    </div>
</div>
