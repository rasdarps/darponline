

  
    <style data-shopify>
      @font-face {
          font-family: Poppins;
          font-weight: 400;
          font-style: normal;
          src: url("{{asset('client/fonts/poppins/poppins_n4.0ba78fa5af9b0e1a374041b3ceaadf0a43b41362.woff2')}}") format("woff2"),
              url("{{asset('client/fonts/poppins/poppins_n4.214741a72ff2596839fc9760ee7a770386cf16ca.woff')}}") format("woff");
      }

      @font-face {
          font-family: Jost;
          font-weight: 400;
          font-style: normal;
          src: url("{{asset('client/fonts/jost/jost_n4.d47a1b6347ce4a4c9f437608011273009d91f2b7.woff2')}}") format("woff2"),
              url("{{asset('client/fonts/jost/jost_n4.791c46290e672b3f85c3d1c651ef2efa3819eadd.woff')}}") format("woff");
      }

              /* ----------------------------------------------------
      Normalize    071C1F   0E292D
      ---------------------------------------------------- */
      :root {
          --ltn__primary-color: #071c1f;
          --ltn__secondary-color: #f8796c;
          --ltn__primary-color-2: #041113;
          /* Darken */
          --ltn__primary-color-3: #133236;
          /* Lighten */
          --ltn__heading-color: #071c1f;
          --ltn__paragraph-color: #071c1f;
          --ltn__heading-font: Jost, sans-serif;
          --ltn__body-font: Poppins, sans-serif;


          /* Footer ACD2D8 8CB2B2  */
          --ltn__color-1: #8cb2b2;
          --ltn__color-2: #ACD2D8;
          --ltn__color-3: #A3BCC0;
          --ltn__color-4: #84A2A6;
          /* Gradient Colors */
          --gradient-color-1: linear-gradient(90deg, rgba(242, 139, 194, 1) 0%, rgba(216, 177, 242, 1) 50%);
          --gradient-color-2: linear-gradient(to top, rgba(7, 28, 31, 0) 0%, rgba(7, 28, 31, 1) 90%);
          --gradient-color-3: linear-gradient(to bottom, rgba(7, 28, 31, 0) 0%, rgba(7, 28, 31, 1) 90%);
          --gradient-color-4: linear-gradient(to top, rgba(242, 246, 247, 0) 0%, rgba(242, 246, 247, 1) 90%);
          /* Background Colors */
          --section-bg-1: #F2F6F7;
          /* White */
          --section-bg-2: #071c1f;
          /* Black */
          /* Border Colors */
          --border-color-1: #e5eaee;
          /* White */
          --border-color-2: #1e2021;
          /* Black */
          --border-color-3: #576466;
          /* Black */
          --border-color-4: #eb6954;
          /* Red */
          --border-color-5: #bc3928;
          /* Red */
          --border-color-6: #103034;
          /* Black */
          --border-color-7: #d1dae0;
          /* White */
          --border-color-8: #f6f6f6;
          /* White */
          --border-color-9: #e4ecf2;
          /* White */
          --border-color-10: #ebeeee;
          /* White */
          --border-color-11: #ededed;
          /* White */
          --border-color-12: #e1e6ff;
          /* White */
          /* Box Shadow Colors */
          --ltn__box-shadow-1: 0 16px 32px 0 rgba(7, 28, 31, 0.1);
          --ltn__box-shadow-2: 0 0 4px rgba(0, 0, 0, 0.1);
          /* like border */
          --ltn__box-shadow-3: 0 1px 6px 0 rgba(32, 33, 36, .28);
          /* like border GGL */
          --ltn__box-shadow-4: 0 5px 20px 0 rgba(23, 44, 82, 0.1);
          --ltn__box-shadow-5: 0 8px 16px 0 rgba(93, 93, 93, 0.1);
          --ltn__box-shadow-6: 0 0 25px 2px rgba(93, 93, 93, 0.2);

          /* Common Colors */
          --black: #000000;
          --black-2: #22355B;
          --white: #fff;
          --white-2: #F2F6F7;
          --white-3: #e8edee;
          --white-4: #e6ecf0;
          --white-5: #f0f4f7;
          --white-6: #f1f1f1;
          --white-7: #F7F7F7;
          --white-8: #FAFAFA;
          --white-9: #F2F7FA;
          --red: #FF0000;
          --red-2: #f34f3f;
          --red-3: #DB483B;
          --silver: #C0C0C0;
          --gray: #808080;
          --maroon: #800000;
          --yellow: #FFFF00;
          --olive: #808000;
          --lime: #00FF00;
          --green: #008000;
          --aqua: #00FFFF;
          --teal: #008080;
          --blue: #0000FF;
          --navy: #000080;
          --fuchsia: #FF00FF;
          --purple: #800080;
          --pink: #FFC0CB;
          --nude: #ebc8b2;
          --orange: #ffa500;
          --ratings: #FFB800;
          /* social media colors */
          --facebook: #365493;
          --twitter: #3CF;
          --linkedin: #0077B5;
          --pinterest: #c8232c;
          --dribbble: #ea4c89;
          --behance: #131418;
          --google-plus: #dd4b39;
          --instagram: #e4405f;
          --vk: #3b5998;
          --wechat: #7bb32e;
          --youtube: #CB2027;
          --email: #F89A1E;

      }
  </style><!-- Header hook for plugins -->

   <!-- Link your style.css and responsive.css files below -->
   <link href="{{asset('client/shop/t/89/assets/global.css')}}" rel="stylesheet"
   type="text/css" media="all" />
<link href="{{asset('client/shop/t/89/assets/font-icons.css')}}"
   rel="stylesheet" type="text/css" media="all" />
<link href="{{asset('client/shop/t/89/assets/bootstrap.min.css')}}"
   rel="stylesheet" type="text/css" media="all" />
<link href="{{asset('client/shop/t/89/assets/plugins.css')}}"
   rel="stylesheet" type="text/css" media="all" />
<link href="{{asset('client/shop/t/89/assets/aos.min.css')}}"
   rel="stylesheet" type="text/css" media="all" />
<link href="{{asset('client/shop/t/89/assets/style.css')}}" rel="stylesheet"
   type="text/css" media="all" />
<!-- Make your theme CSS calls here -->
<link href="{{asset('client/shop/t/89/assets/theme-custom.css')}}"
   rel="stylesheet" type="text/css" media="all" />

   <link rel="stylesheet" href=  "{{ asset('notiflix/notiflix-3.2.8.min.css') }}"/>

   <style class='jdgm-settings-style'>
    .jdgm-xx {
        left: 0
    }

    :root {
        --jdgm-primary-color: #108474;
        --jdgm-secondary-color: rgba(16, 132, 116, 0.1);
        --jdgm-star-color: #108474;
        --jdgm-write-review-text-color: white;
        --jdgm-write-review-bg-color: #108474;
        --jdgm-paginate-color: #108474;
        --jdgm-border-radius: 0;
        --jdgm-reviewer-name-color: #108474
    }

    .jdgm-histogram__bar-content {
        background-color: #108474
    }

    .jdgm-rev[data-verified-buyer=true] .jdgm-rev__icon.jdgm-rev__icon:after,
    .jdgm-rev__buyer-badge.jdgm-rev__buyer-badge {
        color: white;
        background-color: #108474
    }

    .jdgm-review-widget--small .jdgm-gallery.jdgm-gallery .jdgm-gallery__thumbnail-link:nth-child(8) .jdgm-gallery__thumbnail-wrapper.jdgm-gallery__thumbnail-wrapper:before {
        content: "See more"
    }

    @media only screen and (min-width: 768px) {
        .jdgm-gallery.jdgm-gallery .jdgm-gallery__thumbnail-link:nth-child(8) .jdgm-gallery__thumbnail-wrapper.jdgm-gallery__thumbnail-wrapper:before {
            content: "See more"
        }
    }

    .jdgm-prev-badge[data-average-rating='0.00'] {
        display: none !important
    }

    .jdgm-author-all-initials {
        display: none !important
    }

    .jdgm-author-last-initial {
        display: none !important
    }

    .jdgm-rev-widg__title {
        visibility: hidden
    }

    .jdgm-rev-widg__summary-text {
        visibility: hidden
    }

    .jdgm-prev-badge__text {
        visibility: hidden
    }

    .jdgm-rev__prod-link-prefix:before {
        content: 'about'
    }

    .jdgm-rev__variant-label:before {
        content: 'Variant: '
    }

    .jdgm-rev__out-of-store-text:before {
        content: '(out of store)'
    }

    @media only screen and (min-width: 768px) {

        .jdgm-rev__pics .jdgm-rev_all-rev-page-picture-separator,
        .jdgm-rev__pics .jdgm-rev__product-picture {
            display: none
        }
    }

    @media only screen and (max-width: 768px) {

        .jdgm-rev__pics .jdgm-rev_all-rev-page-picture-separator,
        .jdgm-rev__pics .jdgm-rev__product-picture {
            display: none
        }
    }

    .jdgm-preview-badge[data-template="product"] {
        display: none !important
    }

    .jdgm-preview-badge[data-template="collection"] {
        display: none !important
    }

    .jdgm-preview-badge[data-template="index"] {
        display: none !important
    }

    .jdgm-review-widget[data-from-snippet="true"] {
        display: none !important
    }

    .jdgm-verified-count-badget[data-from-snippet="true"] {
        display: none !important
    }

    .jdgm-carousel-wrapper[data-from-snippet="true"] {
        display: none !important
    }

    .jdgm-all-reviews-text[data-from-snippet="true"] {
        display: none !important
    }

    .jdgm-medals-section[data-from-snippet="true"] {
        display: none !important
    }

    .jdgm-ugc-media-wrapper[data-from-snippet="true"] {
        display: none !important
    }

    .jdgm-rev__transparency-badge[data-badge-type="review_collected_via_store_invitation"] {
        display: none !important
    }

    .jdgm-rev__transparency-badge[data-badge-type="review_collected_from_another_provider"] {
        display: none !important
    }

    .jdgm-rev__transparency-badge[data-badge-type="review_collected_from_store_visitor"] {
        display: none !important
    }

    .jdgm-rev__transparency-badge[data-badge-type="review_written_in_etsy"] {
        display: none !important
    }

    .jdgm-rev__transparency-badge[data-badge-type="review_written_in_google_business"] {
        display: none !important
    }

    .jdgm-rev__transparency-badge[data-badge-type="review_written_in_shop_app"] {
        display: none !important
    }

    .jdgm-rev__transparency-badge[data-badge-type="review_earned_for_future_purchase"] {
        display: none !important
    }

    .jdgm-review-snippet-widget .jdgm-rev-snippet-widget__cards-container .jdgm-rev-snippet-card {
        border-radius: 8px;
        background: #fff
    }

    .jdgm-review-snippet-widget .jdgm-rev-snippet-widget__cards-container .jdgm-rev-snippet-card__rev-rating .jdgm-star {
        color: #108474
    }

    .jdgm-review-snippet-widget .jdgm-rev-snippet-widget__prev-btn,
    .jdgm-review-snippet-widget .jdgm-rev-snippet-widget__next-btn {
        border-radius: 50%;
        background: #fff
    }

    .jdgm-review-snippet-widget .jdgm-rev-snippet-widget__prev-btn>svg,
    .jdgm-review-snippet-widget .jdgm-rev-snippet-widget__next-btn>svg {
        fill: #000
    }

    .jdgm-full-rev-modal.rev-snippet-widget .jm-mfp-container .jm-mfp-content,
    .jdgm-full-rev-modal.rev-snippet-widget .jm-mfp-container .jdgm-full-rev__icon,
    .jdgm-full-rev-modal.rev-snippet-widget .jm-mfp-container .jdgm-full-rev__pic-img,
    .jdgm-full-rev-modal.rev-snippet-widget .jm-mfp-container .jdgm-full-rev__reply {
        border-radius: 8px
    }

    .jdgm-full-rev-modal.rev-snippet-widget .jm-mfp-container .jdgm-full-rev[data-verified-buyer="true"] .jdgm-full-rev__icon::after {
        border-radius: 8px
    }

    .jdgm-full-rev-modal.rev-snippet-widget .jm-mfp-container .jdgm-full-rev .jdgm-rev__buyer-badge {
        border-radius: calc(8px / 2)
    }

    .jdgm-full-rev-modal.rev-snippet-widget .jm-mfp-container .jdgm-full-rev .jdgm-full-rev__replier::before {
        content: 'New99'
    }

    .jdgm-full-rev-modal.rev-snippet-widget .jm-mfp-container .jdgm-full-rev .jdgm-full-rev__product-button {
        border-radius: calc(8px * 6)
    }
</style>

<style class='jdgm-settings-style'></style>




<style class='jdgm-miracle-styles'>
    @-webkit-keyframes jdgm-spin {
        0% {
            -webkit-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            transform: rotate(0deg)
        }

        100% {
            -webkit-transform: rotate(359deg);
            -ms-transform: rotate(359deg);
            transform: rotate(359deg)
        }
    }

    @keyframes jdgm-spin {
        0% {
            -webkit-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            transform: rotate(0deg)
        }

        100% {
            -webkit-transform: rotate(359deg);
            -ms-transform: rotate(359deg);
            transform: rotate(359deg)
        }
    }

    @font-face {
        font-family: 'JudgemeStar';
        src: url("data:application/x-font-woff;charset=utf-8;base64,d09GRgABAAAAAAScAA0AAAAABrAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABGRlRNAAAEgAAAABoAAAAcbyQ+3kdERUYAAARgAAAAHgAAACAAMwAGT1MvMgAAAZgAAABGAAAAVi+vS9xjbWFwAAAB8AAAAEAAAAFKwBMjvmdhc3AAAARYAAAACAAAAAj//wADZ2x5ZgAAAkAAAAEJAAABdH33LXtoZWFkAAABMAAAAC0AAAA2BroQKWhoZWEAAAFgAAAAHAAAACQD5QHQaG10eAAAAeAAAAAPAAAAFAYAAABsb2NhAAACMAAAAA4AAAAOAO4AeG1heHAAAAF8AAAAHAAAACAASgAvbmFtZQAAA0wAAADeAAABkorWfVZwb3N0AAAELAAAACkAAABEp3ubLXgBY2BkYADhPPP4OfH8Nl8ZuJkYQODS2fRrCPr/aSYGxq1ALgcDWBoAO60LkwAAAHgBY2BkYGDc+v80gx4TAwgASaAICmABAFB+Arl4AWNgZGBgYGPQYWBiAAIwyQgWc2AAAwAHVQB6eAFjYGRiYJzAwMrAwejDmMbAwOAOpb8ySDK0MDAwMbByMsCBAAMCBKS5pjA4PGB4wMR44P8BBj3GrQymQGFGkBwAjtgK/gAAeAFjYoAAEA1jAwAAZAAHAHgB3crBCcAwDEPRZydkih567CDdf4ZskmLwFBV8xBfCaC4BXkOUmx4sU0h2ngNb9V0vQCxaRKIAevT7fGWuBrEAAAAAAAAAAAA0AHgAugAAeAF9z79Kw1AUx/FzTm7un6QmJtwmQ5Bg1abgEGr/BAqlU6Gju+Cgg1MkQ/sA7Vj7BOnmO/gUvo2Lo14NqIO6/IazfD8HEODtmQCfoANwNsyp2/GJt3WKQrd1NLiYYWx2PBqOsmJMEOznPOTzfSCrhAtbbLdmeFLJV9eKd63WLrZcIcuaEVdssWCKM6pLCfTVOYbz/0pNSMSZKLIZpvh78sAUH6PlMrreTCabP9r+Z/puPZ2ur/RqpQHgh+MIegCnXeM4MRAPjYN//5tj4ZtTjkFqEdmeMShlEJ7tVAly2TAkx6R68Fl4E/aVvn8JqHFQ4JS1434gXKcuL31dDhzs3YbsEOAd/IU88gAAAHgBfY4xTgMxEEVfkk0AgRCioKFxQYd2ZRtpixxgRU2RfhU5q5VWseQ4JdfgAJyBlmNwAM7ABRhZQ0ORwp7nr+eZAa54YwYg9zm3ynPOeFRe8MCrciXOh/KSS76UV5L/iDmrLiS5AeU519wrL3jmSbkS5115yR2fyivJv9kx0ZMZ2RLZw27q87iNQi8EBo5FSPIMw3HqBboi5lKTGAGDp8FKXWP+t9TU01Lj5His1Ba6uM9dTEMwvrFmbf5GC/q2drW3ruXUhhsCiQOjznFlCzYhHUZp4xp76vsvQh89CQAAeAFjYGJABowM6IANLMrEyMTIzMjCXpyRWJBqZshWXJJYBKOMAFHFBucAAAAAAAAB//8AAngBY2BkYGDgA2IJBhBgAvKZGViBJAuYxwAABJsAOgAAeAFjYGBgZACCk535hiD60tn0azAaAEqpB6wAAA==") format("woff");
        font-weight: normal;
        font-style: normal
    }

    .jdgm-star {
        font-family: 'JudgemeStar';
        display: inline !important;
        text-decoration: none !important;
        padding: 0 4px 0 0 !important;
        margin: 0 !important;
        font-weight: bold;
        opacity: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale
    }

    .jdgm-star:hover {
        opacity: 1
    }

    .jdgm-star:last-of-type {
        padding: 0 !important
    }

    .jdgm-star.jdgm--on:before {
        content: "\e000"
    }

    .jdgm-star.jdgm--off:before {
        content: "\e001"
    }

    .jdgm-star.jdgm--half:before {
        content: "\e002"
    }

    .jdgm-widget * {
        margin: 0;
        line-height: 1.4;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        -webkit-overflow-scrolling: touch
    }

    .jdgm-hidden {
        display: none !important;
        visibility: hidden !important
    }

    .jdgm-temp-hidden {
        display: none
    }

    .jdgm-spinner {
        width: 40px;
        height: 40px;
        margin: auto;
        border-radius: 50%;
        border-top: 2px solid #eee;
        border-right: 2px solid #eee;
        border-bottom: 2px solid #eee;
        border-left: 2px solid #ccc;
        -webkit-animation: jdgm-spin 0.8s infinite linear;
        animation: jdgm-spin 0.8s infinite linear
    }

    .jdgm-prev-badge {
        display: block !important
    }

      /* Error styles */
      .is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linecap='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .error-text {
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        /* Global alert styles */
        #global_error_alert {
            border-left: 4px solid #dc3545;
            background-color: #f8d7da;
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 4px;
        }

        #global_error_alert ul {
            margin-bottom: 0;
            margin-top: 5px;
            padding-left: 20px;
        }

        /* Password strength indicator */
        .password-strength {
            font-size: 12px;
        }

        /* Loading state for button */
        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Success animation for inputs */
        .is-valid {
            border-color: #28a745 !important;
        }

        /* Error highlight animation */
            .error-highlight {
                animation: shake 0.5s ease-in-out;
            }

            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }

            /* Smooth scrolling for the whole page */
            html {
                scroll-behavior: smooth;
            }

            /* Focus style for error fields */
            .is-invalid:focus {
                border-color: #dc3545;
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
            }

            /* Error message styling */
            .error-text {
                font-size: 12px;
                margin-top: 5px;
                display: block;
                animation: fadeIn 0.3s ease-in;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-5px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Global error alert styling */
            #global_error_alert {
                position: sticky;
                top: 20px;
                z-index: 1000;
                animation: slideDown 0.4s ease-out;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .ltn__utilize-overlay {
            z-index: 3999!important;
    
            }

            .ltn__utilize {
            z-index: 4000 !important;
    
            }
</style>

@yield('header')
