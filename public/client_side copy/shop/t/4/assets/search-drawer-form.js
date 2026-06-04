class SearchDrawerForm extends HTMLElement {
  constructor() {
    super();
    this.filterData = [];
    this.form = this.querySelector('form');
    this.content = this.querySelector('[data-search-autocomplete]');
    this.input = this.querySelector('input[type="search"]');
    this.suggested = this.dataset.searchSuggested;
    this.heading = this.querySelector('[data-search-drawer-heading]');
    this.result = this.querySelector('[data-search-drawer-result]');
    this.bottom = this.querySelector('[data-search-drawer-bottom]');

    this.debouncedOnSubmit = debounce((event) => {
      this.result.classList.add('is-loading');
      this.onSubmitHandler(event);
    }, 500);
    this.form.addEventListener('input', this.debouncedOnSubmit.bind(this));
    this.form.addEventListener('submit', function(event){
      event.preventDefault();
      const formData = new FormData(event.target.closest('form'));
      const searchParams = new URLSearchParams(formData).toString();
      const searchUrl = searchParams.split('&product_type=');
      const searchTerms = searchUrl[0].trim();
      const searchType = searchUrl[1] ? searchUrl[1].trim() : '*';
      
      const url = (searchType === '*') ? `/search?${searchTerms}` : `/search?${searchTerms}+product_type:${searchType}`;
      
      location.href = url;
    });
  }
  onSubmitHandler(event) {
    event.preventDefault();
    if (this.content.dataset.searchAutocomplete === 'true') {
      if (this.input.value == '' && this.suggested === 'true') {
      	this.heading.classList.add('search-suggested');
        this.result.classList.add('search-suggested');
        this.bottom.classList.add('search-suggested');
        this.result.classList.remove('is-loading');
      } else {
        if (this.suggested === 'true') {
          this.heading.classList.remove('search-suggested');
          this.result.classList.remove('search-suggested');
          this.bottom.classList.remove('search-suggested');
        }
        const formData = new FormData(event.target.closest('form'));
        const searchParams = new URLSearchParams(formData).toString();
        this.renderPage(searchParams, event);
      }
    }
  }
  renderPage(searchParams, event) {
    const searchUrl = searchParams.split('&product_type=');
    const searchTerms = searchUrl[0].trim();
    const searchType = searchUrl[1] ? searchUrl[1].trim() : '*';
    
    const url = (searchType === '*') ? `/search?view=autocomplete-drawer&${searchTerms}` : `/search?view=autocomplete-drawer&${searchTerms}+product_type:${searchType}`;
    const urlAction = (searchType === '*') ? `/search?${searchTerms}` : `/search?${searchTerms}+product_type:${searchType}`;
    const filterDataUrl = element => element.url === url;
    this.filterData.some(filterDataUrl) ?
      this.renderSectionFromCache(filterDataUrl, event) :
      this.renderSectionFromFetch(url, urlAction, event);
  }
  renderSectionFromFetch(url, urlAction, event) {
    fetch(url)
      .then(response => response.text())
      .then((responseText) => {
      	const html = new DOMParser().parseFromString(responseText, 'text/html');
        this.querySelector('.search-results__action').href = urlAction;
        this.filterData = [...this.filterData, { html, url }];
      
        this.renderProductGrid(html.querySelector('.search-results__layout'));
      });
  }
  renderSectionFromCache(filterDataUrl, event) {
    const html = this.filterData.find(filterDataUrl).html;
    this.renderProductGrid(html.querySelector('.search-results__layout'));
  }
  renderProductGrid(html) {
    this.result.classList.remove('is-loading');
    this.content.innerHTML = html.innerHTML;
  }
}
customElements.define('search-drawer-form', SearchDrawerForm);