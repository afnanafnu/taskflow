@props([
    'items' => [],
    'columns' => [],
    'title' => null,
    'searchPlaceholder' => 'Search...',
    'emptyMessage' => 'No records found.',
])

<div
    x-data="dataTable(@js($items))"
    class="card border-0 shadow-sm data-table-card"
>

    {{-- Header --}}
    <div class="card-header bg-white border-0 p-3">

        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">

            @if($title)

                <div>
                    <h5 class="mb-1">
                        {{ $title }}
                    </h5>
                </div>

            @endif


            <div class="data-table-search">

                <div class="input-group">

                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        class="form-control"
                        placeholder="{{ $searchPlaceholder }}"
                        x-model="search"
                        @input="resetPage()"
                    >

                </div>

            </div>

        </div>

    </div>


    {{-- Table --}}
    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>

                    @foreach($columns as $column)

                        <th
                            @if($column['sortable'] ?? true)
                                class="data-table-sortable"
                                @click="sort('{{ $column['key'] }}')"
                            @endif
                        >

                            <div class="d-flex align-items-center gap-1">

                                {{ $column['label'] }}

                                @if($column['sortable'] ?? true)

                                    <span
                                        class="text-muted small"
                                        x-text="sortIcon('{{ $column['key'] }}')"
                                    ></span>

                                @endif

                            </div>

                        </th>

                    @endforeach


                    @if($actions ?? false)

                        <th>
                            Actions
                        </th>

                    @endif

                </tr>

            </thead>


            <tbody>

                <template
                    x-for="item in paginatedItems"
                    :key="item.id"
                >

                    <tr>

                        @foreach($columns as $column)

                            <td>
                                <span
                                    x-text="item['{{ $column['key'] }}'] ?? '-'"
                                ></span>
                            </td>

                        @endforeach


                        @if($actions ?? false)

                            <td>
                                <div class="d-flex gap-1">

                                    {{ $actions }}

                                </div>
                            </td>

                        @endif

                    </tr>

                </template>


                <tr x-show="paginatedItems.length === 0">

                    <td
                        colspan="{{ count($columns) + ($actions ?? false ? 1 : 0) }}"
                        class="text-center py-5"
                    >

                        <div class="text-muted">

                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                            <div>
                                {{ $emptyMessage }}
                            </div>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    {{-- Footer --}}
    <div class="card-footer bg-white border-0 p-3">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3">

            <div class="small text-muted">

                Showing
                <strong x-text="startItem"></strong>
                -
                <strong x-text="endItem"></strong>

                of

                <strong x-text="filteredItems.length"></strong>

                results

            </div>


            <div class="d-flex align-items-center gap-2">

                <label class="small text-muted mb-0">
                    Show
                </label>

                <select
                    class="form-select form-select-sm"
                    style="width: 75px"
                    x-model.number="perPage"
                    @change="resetPage()"
                >

                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>

                </select>

            </div>


            <div class="d-flex align-items-center gap-1">

                <button
                    type="button"
                    class="btn btn-sm btn-outline-secondary"
                    @click="previousPage()"
                    :disabled="page === 1"
                >
                    <i class="bi bi-chevron-left"></i>
                </button>


                <template
                    x-for="pageNumber in totalPages"
                    :key="pageNumber"
                >

                    <button
                        type="button"
                        class="btn btn-sm"
                        :class="page === pageNumber
                            ? 'btn-primary'
                            : 'btn-outline-secondary'"
                        @click="goToPage(pageNumber)"
                        x-text="pageNumber"
                    ></button>

                </template>


                <button
                    type="button"
                    class="btn btn-sm btn-outline-secondary"
                    @click="nextPage()"
                    :disabled="page === totalPages"
                >
                    <i class="bi bi-chevron-right"></i>
                </button>

            </div>

        </div>

    </div>

</div>