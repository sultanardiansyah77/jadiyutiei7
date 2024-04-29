<style type="text/tailwindcss">
    @tailwind base;
        @tailwind components;
        @tailwind utilities;

        @layer base {
            ol {
                @apply list-decimal pl-4 pt-2
            }
            h1 {
                @apply text-2xl
            }
            h2 {
                @apply text-xl
            }
        }

        @layer components {
            .color-primary{
                @apply bg-[#212529]
            }
            .color-secondary{
                @apply bg-[#1B2328]
            }
            .color-third {
                @apply bg-[#0E1215]
            }
            .color-fourth {
                @apply bg-[#E0D8D2]
            }
            .bg-fifth{
                @apply bg-sky-600
            }
            .color-fifth{
                @apply text-sky-600
            }
            .text-color-fourth {
                @apply  text-[#E0D8D2]
            }
            .m-padd {
                @apply px-[120px];
            }
            .m-second-padd{
                @apply px-[280px];
            }
            .menu_list {
                @apply sm:text-[13px] text-color-fourth transition duration-200 hover:text-sky-600
            }
            .produk_item{
                @apply rounded-[20px] sm:rounded-[30px]  h-[150px] bg-[#E0D8D2]
            }
            .m-mt {
                @apply mt-[20px]
            }
            .menu_footer{
                @apply flex flex-col justify-start  h-full items-start text-color-fourth p-[20px]
            }
            .msc-padd-small{
                @apply  px-[20px]
            }
            .menu_sidebar {
                @apply block py-4 px-4 rounded transition duration-200 hover:bg-fifth hover:text-white hover:font-bold sm:text-[14px] text-[13px]
            }
            .accordion-item{
                @apply color-primary
            }
            .accordion-header{
                @apply color-secondary text-color-fourth
            }
            .accordion-body{
                @apply text-color-fourth p-1
            }
            .panel-heading {
                @apply bg-red-100
            }
            .accordion-button{
                @apply  text-color-fourth
            }
            .form-input{
                @apply w-full border border-gray-300 px-4 py-2 focus:outline-none rounded-lg
            }
            .item-table{
                @apply  px-2 py-4 text-sm sm:w-[50%]  border border-slate-700 group-hover:bg-fifth cursor-pointer duration-200 delay-100
            }
        }

        .m-shadow {
            box-shadow: 0px 9px 6px rgba(0, 0, 0, 0.25);
        }
        .m-shadow-active{
            box-shadow: 0px 9px 6px rgb(2 132 199 / var(--tw-bg-opacity));
            /* box-shadow: 0px 9px 6px rgba(236, 232, 232, 0.25); */
        }

        body {
        position: relative;
        height: 100%;
      }

        /* .row-cols-3 > * {
            flex: 0 0 auto;
            width: 33.3333333333%;
        }

        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-left: calc(var(--bs-gutter-x)*-.5);
            margin-right: calc(var(--bs-gutter-x)*-.5);
            margin-top: calc(var(--bs-gutter-y)*-1);
        }

        .bg-transparent {
            --bs-bg-opacity: 1;
            background-color: transparent !important;
        }

        .h-100 {
            height: 100% !important;
        }

         */

        @media only screen and (min-width: 600px) {

        }
        .animate-scale:hover {
                transform: scale(1.1);
                transition: .6s ease;
            }
            .scrollbar {
                background-color: #F5F5F5;
                float: left;
                height: 300px;
                margin-bottom: 25px;
                margin-left: 22px;
                margin-top: 40px;
                width: 65px;
                overflow-y: scroll;
            }




      /* .swiper { */
        /* width: 100%; */
        /* height: 100%; */
      /* } */
/*
      .swiper-slide {
        background-position: center;
        background-size: cover;
      }

      .swiper-slide img {
        display: block;
        width: 100%;
      } */

      /* .swiper {
        width: 50%;
        padding-top: 50px;
        padding-bottom: 50px;
      } */

      .swiper {
          width: 100%;
          height: 100%;
          /* padding-top: 50px;
          padding-bottom: 50px; */
        }

        .swiper-slide {
          background-position: center;
          background-size: cover;
          width: 80%;
          height: 300px;
        }

        .swiper-slide img {
          display: block;
          width: 100%;
        }
        .swiper-button-next,
          .swiper-button-prev{
              /* background-color: #fe6c1783; */
              background-color: #fe6c17;
              right:10px;
              padding: 30px;
              color: rgb(255, 255, 255) !important;
              fill: black !important;
              stroke: black !important;
              border-radius: 100px
          }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            /* background-color: #fe6c1783; */
            background-color: #fe6c17 !important;
            right:10px;
            padding: 13px;
            color: rgb(255, 255, 255) !important;
            fill: black !important;
            stroke: black !important;
            border-radius: 100px
        }
        .swiper-button-next:after,
        .swiper-button-prev:after{
          font-size:20px !important;
        }
        .swiper-button-next:hover,
        .swiper-button-prev:hover{
          background-color: #fe6c17;
        }
        .swiper-pagination-bullet-active{
          background-color:#fe6c17;
        }
    /* background-color: rgba(var(--bs-dark-rgb),var(--bs-bg-opacity)) !important */

</style>