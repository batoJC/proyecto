<header>
        <head>
            <style>
                /** Define the margins of your page **/
                @page {
                    margin: 100px 25px;
                }
    
                header {
                    position: fixed;
                    top: -60px;
                    left: 0px;
                    right: 0px;
                    height: 50px;
                    color: black;
                    text-align: center;
                    /* line-height: 20px; */
                }

                .logo{
                    height: 60px;;
                    width: auto;
                    float: left;
                    display: inline-block;
                    margin-right: 0px;
                }

                hr{
                    margin: -20px 0px -35px 0px;
                    border: 0;
                    border-top: 1px solid black;
                }

                .logo img{
                    height: 100%;
                    width: auto;
                }

                .info{
                    float: left;
                    width: auto;
                    display: inline-block;
                    height: 50px;
                }
    
                footer {
                    position: fixed; 
                    bottom: -100px; 
                    left: 0px; 
                    right: 0px;
                    height: 40px; 
                    color: black;
                    text-align: center;
                    /* line-height: 35px; */
                }

                .head{
                    text-align: center !important;
                    margin-right: 125px !important;
                }

                h3{
                    font-weight: 100 !important;
                    margin: 2px 0px 2px 0px !important;
                }

                main{
                    margin-left: 50px;
                    margin-right: 50px;
                    bottom: 20px;
                }

            </style>
        </head>
        <div class="logo">
            @if (Auth::user()->conjunto->logo)
                <img src="{{ public_path() }}/imgs/logos_conjuntos/{{Auth::user()->conjunto->logo }}" alt="">
            @endif
        </div>
        <div class="info">
            <h3 class="head">{{Auth::user()->conjunto->nombre}}.</h3>
            <h3 class="head">Nit. {{ Auth::user()->conjunto->nit }}</h3>
        </div>
    </header>
    {{-- <hr> --}}
    <br>
    <hr>
    <br>
    <footer>
        <hr>
        Email: {{Auth::user()->conjunto->correo}} - Dirección: {{Auth::user()->conjunto->direccion}} - Teléfono: {{Auth::user()->conjunto->tel_cel}}
    </footer>