@extends('layout.app')

@section('content')
<style>
    .boxinner span#addon-wrapping {
        border-left: none;
        height: 50px;
        position: relative;
        background: none !important;
    }

    .boxinner span#addon-wrapping:hover .hideshowbox {
        display: block !important;
    }

    .hideshowbox {
        padding: 10px;
        left: -120px;
        top: -30px;
        border-radius: 5px;
        background: #fff;
        max-width: 200px !important;
        height: auto;
        position: absolute;
        box-shadow: 0 2px 7px #33333340 !important;
        display: none !important;
    }

    .hideshowbox p {
        font-size: 10px;
        margin: 0;
    }

    .boxinner input.form-control {
        border-right: none;
        padding-left: 25px;
        height: 50px;
    }

    .boxpadding {
        position: relative;
        top: 100px;
        height: 90vh;
        margin-top: 30px;
        overflow-x: scroll;
        margin-right: 20px;
        padding-right: 25px !important;
    }

    .searchi-section {
        margin-bottom: 40px !important;
    }

    .accordion-item h2 button {
        font-size: 20px;
        box-shadow: 0 2px 7px #33333340 !important;
        font-weight: 600;
        border-radius: 5px;
        line-height: 30px;
        text-align: left;
        color: #04498c;
    }

    .accordion-item {
        border-radius: 5px;
        background: none !important;
        border: none !important;
        margin-bottom: 30px;
    }

    .searchi-section h6 {
        margin: 10px 0 0 !important;
    }

    .accordion-body {
        background: transparent;
        padding: 40px 5px 40px !important;
    }

    .accordion-button:not(.collapsed) {
        background-color: #fff !important;
    }

    .boxinner {
        box-shadow: 0px 1px 17.1px 0px #6565651C;
        padding: 50px !important;
        background: #fff;
        border-radius: 5px;
        margin-bottom: 25px;
        min-height: 485px;
    }

    .textbox {
        display: flex;
        height: 55px;
        align-items: center;
        justify-content: space-between;
    }

    .textbox h6 {
        margin: 0;
        font-size: 17px;
        font-weight: 500;
        line-height: 20.57px;
        text-align: left;
        color: #777777;
    }

    .boxinner select.form-select.mt-3 {
        max-height: 50px;
    }

    .imgboxhead h5 {
        margin: 0;
        font-size: 15px;
        color: #777777;
        font-weight: 600;
        line-height: 20.57px;
    }

    .imglogobox {
        display: flex;
        min-height: 315px;
        border: 1px solid #DEDEDE;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .imglogobox h4 {
        color: #005B96;
        font-size: 20px;
        margin-top: 25px;
        font-weight: 400;
        line-height: 24.2px;
        text-align: center;
    }

    .logobox {
        min-height: 315px;
        padding: 20px 30px;
        border: 1px solid #DEDEDE;
    }

    .logobox h4 {
        font-size: 15px;
        font-weight: 400;
        line-height: 18.15px;
        text-align: left;
    }

    .imgarea {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }

    .imglogobox img {
        height: 135px;
        object-fit: cover;
        width: 66%;
    }

    .imgarea img {
        width: 50px;
        object-fit: cover;
        margin-right: 3px;
        height: 50px;
        margin-top: 20px;
    }

    .color-picker-container h4 {
        font-size: 15px;
        font-weight: 500;
        line-height: 18.15px;
        text-align: left;
        color: #005B96;
    }

    .color-picker-container {
        background-color: #FAFAFA;
        padding: 15px;
        border-radius: 8px;
    }

    .tab-container {
        background: #007bff;
        padding: 10px;
        display: flex;
        justify-content: space-around;
        margin-bottom: 10px;
    }

    .tab-container button {
        flex: 1;
        padding: 5px;
        border: 1px solid #fff;
        color: #fff;
        background: none;
        cursor: pointer;
    }

    .tab-container button.active {
        background: #fff !important;
        color: #007bff;
    }

    .color-grid {
        display: grid;
        grid-template-columns: repeat(10, 1fr);
        gap: 2px;
        margin-bottom: 10px;
    }

    .color-cell {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .opacity-slider-container {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .opacity-slider-container input[type="range"] {
        flex: 1;
        margin: 0 10px;
    }

    .opacity-slider-container span {
        width: 40px;
        text-align: center;
    }

    .textbox h2 {
        position: relative;
    }

    .textbox h2,
    .textbox h3 {
        font-size: 18px !important;
        font-weight: 500;
        line-height: 21.78px;
        text-align: left;
        color: #005B96;
        margin: 0;
    }

    .box1 {
        box-shadow: 0px 3px 8.3px 0px #7777777D;
        background: #75BB3F;
        width: 70px;
        height: 35px;
        border-radius: 6px;
    }

    .box2 {
        box-shadow: 0px 3px 8.3px 0px #7777777D;
        border-radius: 6px;
        background: #D29D03;
        width: 70px;
        height: 35px;
    }

    .box3 {
        box-shadow: 0px 3px 8.3px 0px #7777777D;
        border-radius: 6px;
        background: #79193E;
        width: 70px;
        height: 35px;
    }

    .box4 {
        box-shadow: 0px 3px 8.3px 0px #7777777D;
        background: #03C7FB;
        border-radius: 6px;
        width: 70px;
        height: 35px;
    }

    .box5 {
        box-shadow: 0px 3px 8.3px 0px #7777777D;
        background: #B51900;
        border-radius: 6px;
        width: 70px;
        height: 35px;
    }

    .box6 {
        box-shadow: 0px 3px 8.3px 0px #7777777D;
        background: #BE37F3;
        border-radius: 6px;
        width: 70px;
        height: 35px;
    }

    .box7 {
        box-shadow: 0px 3px 8.3px 0px #7777777D;
        background: #FDFC42;
        border-radius: 6px;
        width: 70px;
        height: 35px;
    }

    .colorbox .textbox {
        margin-bottom: 7px;
    }

    .progress-bar-container {
    display: flex;
    justify-content: space-between;
}

#progressBar {
    width: 60%;
    height:40px;
    margin-top:15px;
    color:#005B96;
    background:#005B96;
}

progress {
  color: #005B96 !important;
  background: pink;
}

progress::-webkit-progress-value {
  background: #005B96;
}

progress::-moz-progress-bar {
  background: lightcolor;
}

#dynamicButton { 
    font-size: 16px;
    border: 2px solid #005B96;
    background-color: #005B96;
    color: white;
    cursor: pointer;
    transition: border-radius 0.3s ease; 
  background: #005B96; 
font-weight: 500;
border:none;
border-radius:36.5px;
color:#fff;
padding:25px 60px
}

#dynamicButton:hover {
    background-color: #0056b3;
}

.border-r h6{
  color:#005B96;
font-size: 18px;
font-weight: 400;
line-height: 21.78px;
text-align: left;

}
.butoonbox1  button{
  width: 100%;
  background: #005B96; 
font-size: 17px;
font-weight: 500;
line-height: 20.57px;
text-align: left;
border:none;
color:#fff;border-radius:36.5px;
padding:25px 20px
}
.butoonbox2  button{
  width: 100%;
  background: #005B96; 
font-size: 17px;
font-weight: 500;
line-height: 20.57px;
text-align: center;
border:none;
color:#fff;border-radius:36.5px;
padding:25px 20px
}
.butoonbox3  button{
  width: 100%;
  background: #005B96; 
font-size: 17px;
font-weight: 500;
line-height: 20.57px;
text-align: right;
border:none;
border-radius:36.5px;
color:#fff;
padding:25px 20px
}

.butoonbox button{
  width: 100%;
  background: #005B96; 
font-size: 17px;
font-weight: 500;
line-height: 20.57px;
text-align: right;
border:none;
border-radius:20px;
color:#fff;
padding:25px 20px
}.butoonboxes button{
  width: 100%;
  background: #005B96; 
font-size: 17px;
font-weight: 500;
line-height: 20.57px;
text-align: right;
border:none;
border-radius:
15px 15px 15px 0px;
color:#fff;
padding:25px 20px
}

</style>

<div class="boxpadding">
    <div class="row searchi-section mt-4">
        <div class="col-6 set-boat-heading">
            <h6>AI Agents</h6>
        </div>
        <div class="col-6">
            <div class="search-container">
                <select class="form-control form-select mr-2">
                    <option>All</option>
                    <option>Lead Generation Bot</option>
                    <option>Customer Support Bot</option>
                    <!-- Add more options as needed -->
                </select>
                <div class="input-group set-select mr-2">
                    <input type="text" class="form-control" placeholder="Search Here For Bot">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
                <button class="btn">Build A WhizBot</button>
            </div>
        </div>
    </div>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#About" aria-expanded="true" aria-controls="collapseOne">
                    Website Bot
                </button>
            </h2>
            <div id="About" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="boxinner">
                                <div class="textbox">
                                    <h6>Text</h6>
                                    <div class="imgboxhead">
                                        <h5>Save <img src="{{asset('assets/images/setup/saveicon.png')}}" alt="" class="ms-3"></h5>
                                    </div>
                                </div>
                                <div class="input-group flex-nowrap mt-3">
                                    <input type="text" class="form-control" placeholder="Bot Name" aria-label="Bot Name" aria-describedby="addon-wrapping">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="addon-wrapping"><i class="bi bi-info-circle-fill"></i>
                                            <div class="hideshowbox">
                                                <p>Here is a dummy content</p>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                                <div class="input-group flex-nowrap mt-3">
                                    <input type="text" class="form-control" placeholder="Welcome Text" aria-label="Welcome Text" aria-describedby="addon-wrapping">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="addon-wrapping"><i class="bi bi-info-circle-fill"></i>
                                            <div class="hideshowbox">
                                                <p>Here is a dummy content</p>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                                <div class="input-group flex-nowrap mt-3">
                                    <input type="text" class="form-control" placeholder="Bot Description" aria-label="Bot Description" aria-describedby="addon-wrapping">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="addon-wrapping"><i class="bi bi-info-circle-fill"></i>
                                            <div class="hideshowbox">
                                                <p>Here is a dummy content</p>
                                            </div>
                                        </span>
                                    </div>
                                </div>

                                <select class="form-select mt-3" aria-label="Choose A Font">
                                    <option selected>Choose A Font</option>
                                    <option value="1">Font 1</option>
                                    <option value="2">Font 2</option>
                                    <option value="3">Font 3</option>
                                </select>
                                <select class="form-select mt-3" aria-label="Font Size">
                                    <option selected>Font Size</option>
                                    <option value="1">Size 1</option>
                                    <option value="2">Size 2</option>
                                    <option value="3">Size 3</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="boxinner">
                                <div class="textbox">
                                    <h6>Logo</h6>
                                    <div class="imgboxhead">
                                        <h5>Save <img src="{{asset('assets/images/setup/saveicon.png')}}" alt="" class="ms-3"></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-4 mt-3">
                                        <div class="imglogobox">
                                            <img src="{{asset('assets/images/setup/CompanyLogo.png')}}" alt="">
                                            <h4>Company Logo</h4>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 mt-3">
                                        <div class="logobox">
                                            <h4>Avatar</h4>
                                            <div class="imgarea mt-1">
                                                <img src="{{asset('assets/images/setup/fistic.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <img src="{{asset('assets/images/setup/admin.png')}}" alt="">
                                                <!-- Add more images as needed -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xl-6">
                            <div class="boxinner">
                                <div class="textbox">
                                    <h6>Themes</h6>
                                    <div class="imgboxhead">
                                        <h5>Save <img src="{{asset('assets/images/setup/saveicon.png')}}" alt="" class="ms-3"></h5>
                                    </div>
                                </div>
                                <div class="border-r mt-3 mb-3"> 
                                <h6 class="mb-3">Message Bubbles <i class="bi bi-info-circle-fill ms-4"></i></h6>
                               <div class="row">
                                <div class="col-xl-4"><div class="butoonbox">
                                    <button>
                                    Hello !
                                    </button>
                                  </div></div>
                                <div class="col-xl-4"><div class="butoonboxes">
                                    <button>
                                    Hello !
                                    </button>
                                  </div></div>
                                <div class="col-xl-4"><div class="butoonbox3">
                                    <button>
                                    Hello !
                                    </button>
                                  </div></div>
                               </div>                                 
                                  </div>
                                <div class="border-r mt-5 mb-3"> 
                                  <h6>Option Border Radius <i class="bi bi-info-circle-fill ms-4"></i></h6>
                                <div class="progress-bar-container">
    <input type="range" id="progressBar" value="0" max="100">
    <div class="button-container">
        <button id="dynamicButton">Click Me!</button>
    </div>
</div>
                                </div>

                                <div class="border-r mt-3 mb-3"> 
                                <h6 class="mb-3">Button Text Alignment<i class="bi bi-info-circle-fill ms-4"></i></h6>
                               <div class="row">
                                <div class="col-xl-4">
                                  <div class="butoonbox1">
                                    <button>
                                    Hello !
                                    </button>
                                  </div>
                                </div>
                                <div class="col-xl-4">
                                <div class="butoonbox2"><button>
                                    Hello !
                                    </button></div></div>
                                <div class="col-xl-4">
                                <div class="butoonbox3"><button>
                                    Hello !
                                    </button></div></div>
                               </div>                                 
                                  </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="boxinner">
                                <div class="textbox">
                                    <h6>Themes</h6>
                                    <div class="imgboxhead">
                                        <h5>Save <img src="{{asset('assets/images/setup/saveicon.png')}}" alt="" class="ms-3"></h5>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-xl-6 colorbox">
                                        <div class="textbox">
                                            <h2>Choose a theme <i class="bi bi-info-circle-fill ms-4"></i></h2>
                                            <div class="box1"></div>
                                        </div>

                                        <div class="textbox">
                                            <h3>Header Background</h3>
                                            <div class="box2"></div>
                                        </div>

                                        <div class="textbox">
                                            <h3>Question Background</h3>
                                            <div class="box3"></div>
                                        </div>

                                        <div class="textbox">
                                            <h3>Answer Background</h3>
                                            <div class="box4"></div>
                                        </div>

                                        <div class="textbox">
                                            <h3>Options Background</h3>
                                            <div class="box5"></div>
                                        </div>

                                        <div class="textbox">
                                            <h3>Option Border Color</h3>
                                            <div class="box6"></div>
                                        </div>

                                        <div class="textbox">
                                            <h2>Chat background <i class="bi bi-info-circle-fill ms-4"></i></h2>
                                            <div class="box7"></div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="color-picker-container">
                                            <div class="tab-container">
                                                <button class="active" id="colorTab">Color</button>
                                                <button id="gradientTab">Gradient</button>
                                            </div>

                                            <div class="color-grid" id="colorGrid">
                                                <!-- Color grid cells -->
                                            </div>
                                            <h4>Opacity</h4>
                                            <div class="opacity-slider-container">
                                                <input type="range" id="opacityRange" min="0" max="100" value="100">
                                                <span id="opacityValue">100%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
        // JavaScript to populate the color grid and handle events
        const colors = [
           
"#FF0000", "#FF7F00", "#FFFF00", "#7FFF00", "#00FF00", "#00FF7F", "#00FFFF", "#007FFF", "#0000FF", "#7F00FF",
            "#FF00FF", "#FF007F", "#FF4C4C", "#FF8C4C", "#FFFF4C", "#8CFF4C", "#4CFF4C", "#4CFF8C", "#4CFFFF", "#4C8CFF",
            "#4C4CFF", "#8C4CFF", "#FF4CFF", "#FF4C8C", "#FFB2B2", "#FFCCB2", "#FFFFB2", "#CCFFB2", "#B2FFB2", "#B2FFCC",
            "#B2FFFF", "#B2CCFF", "#B2B2FF", "#CCB2FF", "#FFB2FF", "#FFB2CC", "#FF6666", "#FF9966", "#FFFF66", "#99FF66",
            "#66FF66", "#66FF99", "#66FFFF", "#6699FF", "#6666FF", "#9966FF", "#FF66FF", "#FF6699", "#FF3333", "#FF6633",
            "#FFFF33", "#66FF33", "#33FF33", "#33FF66", "#33FFFF", "#3366FF", "#3333FF", "#6633FF", "#FF33FF", "#FF3366",
            "#FF0000", "#FF3300", "#FFFF00", "#33FF00", "#00FF00", "#00FF33", "#00FFFF", "#0033FF", "#0000FF", "#3300FF",
            "#FF00FF", "#FF0033", "#FF0000", "#FF7F00", "#FFFF00", "#7FFF00", "#00FF00", "#00FF7F", "#00FFFF", "#007FFF", "#0000FF", "#7F00FF",
            "#FF00FF", "#FF007F", "#FF4C4C", "#FF8C4C", "#FFFF4C", "#8CFF4C", "#4CFF4C", "#4CFF8C", "#4CFFFF", "#4C8CFF",
            "#4C4CFF", "#8C4CFF", "#FF4CFF", "#FF4C8C", "#FFB2B2", "#FFCCB2", "#FFFFB2", "#CCFFB2", "#B2FFB2", "#B2FFCC",
             "#CCB2FF", "#FFB2FF", "#FFB2CC", "#FF6666", "#FF9966", "#FFFF66", "#99FF66",
            "#66FF66", "#66FF99,"
        ];

        const colorGrid = document.getElementById('colorGrid');
        const opacityRange = document.getElementById('opacityRange');
        const opacityValue = document.getElementById('opacityValue');

        // Populate color grid
        colors.forEach(color => {
            const cell = document.createElement('div');
            cell.classList.add('color-cell');
            cell.style.backgroundColor = color;
            cell.addEventListener('click', () => {
                console.log(`Selected color: ${color}`);
            });
            colorGrid.appendChild(cell);
        });

        // Handle opacity range change
        opacityRange.addEventListener('input', () => {
            opacityValue.textContent = `${opacityRange.value}%`;
        });

        document.getElementById('progressBar').addEventListener('input', function() {
    var progressValue = this.value;
    var button = document.getElementById('dynamicButton');
    button.style.borderRadius = progressValue + '%';
});
    </script>
@endsection
