<html>
    
    <body>
        
        <style>
            *{
                font-family: sans-serif;
            }

            h5{
                margin: 4px 0px 4px 0px;
            }

            table{
                width: 100%;
            }

            h2{
                text-align: center;
                margin-top: 2px;
                margin-bottom: 2px;
            }


            .title{
                margin-bottom: 5px;
                font-weight: 200;
                font-size: 22px;
            }

            p{
                text-align: justify !important;
            }

            .text-justify{
                text-align: justify !important;
            }

            .titulo{
                color: red;
                text-transform: uppercase;
            }
            

        </style>
        @yield('style')

        @include('admin.PDF.headFooter')
        <main>
            @yield('contenido')
        </main>
    </body>
</html>
