export function dataTable(items = []) {
    return {
        items: items,

        search: '',
        page: 1,
        perPage: 10,

        sortColumn: null,
        sortDirection: 'asc',

        get filteredItems() {
            const term = this.search.toLowerCase().trim();

            let result = this.items.filter(item => {
                if (!term) {
                    return true;
                }

                return Object.values(item).some(value =>
                    String(value ?? '')
                        .toLowerCase()
                        .includes(term)
                );
            });

            if (this.sortColumn) {
                result.sort((a, b) => {
                    let first = a[this.sortColumn];
                    let second = b[this.sortColumn];

                    first = String(first ?? '').toLowerCase();
                    second = String(second ?? '').toLowerCase();

                    if (first < second) {
                        return this.sortDirection === 'asc' ? -1 : 1;
                    }

                    if (first > second) {
                        return this.sortDirection === 'asc' ? 1 : -1;
                    }

                    return 0;
                });
            }

            return result;
        },

        get paginatedItems() {
            const start = (this.page - 1) * this.perPage;

            return this.filteredItems.slice(
                start,
                start + this.perPage
            );
        },

        get totalPages() {
            return Math.max(
                1,
                Math.ceil(
                    this.filteredItems.length / this.perPage
                )
            );
        },

        get startItem() {
            if (this.filteredItems.length === 0) {
                return 0;
            }

            return ((this.page - 1) * this.perPage) + 1;
        },

        get endItem() {
            return Math.min(
                this.page * this.perPage,
                this.filteredItems.length
            );
        },

        sort(column) {
            if (this.sortColumn === column) {
                this.sortDirection =
                    this.sortDirection === 'asc'
                        ? 'desc'
                        : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }

            this.page = 1;
        },

        sortIcon(column) {
            if (this.sortColumn !== column) {
                return '';
            }

            return this.sortDirection === 'asc'
                ? '↑'
                : '↓';
        },

        previousPage() {
            if (this.page > 1) {
                this.page--;
            }
        },

        nextPage() {
            if (this.page < this.totalPages) {
                this.page++;
            }
        },

        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.page = page;
            }
        },

        resetPage() {
            this.page = 1;
        }
    };
}