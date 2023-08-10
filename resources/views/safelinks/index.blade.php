@extends('layouts.app')
<?php
\App\Library\AssetLib::library('jquery-steps','wizard');
?>
@section('content')
    <style type="text/css">
        .map_canvas {
            height: 100%;
            width: 100%;
            margin: 0px;
            padding: 0px
        }

        .product-selected {
            border: 3px solid #3A61F6 !important;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <form id="contact" action="#">
                <div>
                    <input type="hidden" name="sections" id="sections" value="1">
                    <h3>Create Safelink</h3>
                    <section>
                        <div class="create_safelink_block border p-4 mb-2" data-sec="1">
                            <div class="row portfolio-grid">
                                <h5>A. Choose Your Safelink Devices</h5>
                                <input type="hidden" name="prod[]" required>
                                @foreach($master_products as $master_product)
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 border prod" onclick="selected_product($(this))" data-product-name="{{ $master_product['Name'] }}">
                                        <figure class="effect-text-in ">
                                            <img  src="{{ !empty($master_product['attachment']['attachment_url']) ? url('/uploads/master_products/'.$master_product['attachment']['attachment_url']) : '' }}" alt="profile image">
                                            <figcaption>
                                                <!--<h4>{{ $master_product['Name'] }}</h4>-->
                                                <p> Choose Plan</p>


                                                <input type="hidden" name="prod_id[]" value="{{ $master_product['id'] }}">
                                                <input type="hidden" id="prod_name" name="prod_name[]" value="{{ $master_product['Name'] }}">
                                                <input type="hidden" name="prod_price[]" value="{{ !empty($master_product['Price']) ? $master_product['Price'] : 0 }}">
                                                <input type="hidden" name="prod_img[]" value="{{ !empty($master_product['attachment']['attachment_url']) ? url('/uploads/master_products/thumb/'.$master_product['attachment']['attachment_url']) : '' }}">
                                            </figcaption>
                                        </figure>
                                    </div>
                                @endforeach
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-5">
                                    <h5>B. Safelink Identification</h5>

                                    <div class="form-group row mt-4">
                                        <label for="exampleInput" class="col-sm-4 col-form-label">Safelink ID</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" id="safelink_id" placeholder="Safelink ID" name="safelink_id[]" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="exampleInput" class="col-sm-4 col-form-label">Safelink Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="safelink_name" placeholder="Safelink Name" name="safelink_name[]" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="exampleInput" class="col-sm-4 col-form-label">Country</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="country_id" name="country_id[]">
                                                <option value="">Select</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="exampleInput" class="col-sm-4 col-form-label">Address</label>
                                        <div class="col-sm-8">
                                            <textarea cols="10" rows="5" class="form-control" id="address" name="address[]"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="exampleInputUsername2" class="col-sm-4 col-form-label">Location</label>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" id="lat-span" placeholder="Lat" name="lat[]">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" id="lon-span" placeholder="Long" name="long[]">
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="exampleInputUsername2" class="col-sm-4 col-form-label">Subscription Terms</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="exampleInputUsername2" name="subscription_terms[]" required>
                                                <option value="">Select</option>
                                                @foreach($subscription_terms as $subscription_term)
                                                    <option value="{{ $subscription_term['Term'] }}">{{ $subscription_term['Name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-7">

                                    <div id="map_canvas_1" class="map_canvas" style="border: 2px solid #3872ac;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="more_blocks"></div>

                        <div class="row">
                            <label for="exampleInputUsername2" class="col-sm-5 col-form-label"></label>
                            <div class="col-sm-7">
                                <button type="button" class="btn btn-secondary" onclick="add_more();">Add More</button>
                            </div>
                        </div>

                    </section>

                    <h3>Order</h3>
                    <section>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table" id="order_table">
                                    <thead>
                                    <tr>
                                        <th>Safelink ID</th>
                                        <th>Safelink Name</th>
                                        <th>Duration</th>
                                        <th>Safelink Devices</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-5 billing-info">
                                <h5>Select Billing</h5>
                                <hr>
                                <div class="form-group row mt-4">
                                    <label for="exampleInput" class="col-sm-4 col-form-label">Choose Exisiting Billing</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="exampleInputUsername2" name="exist_billing[]" onchange="exist_billing($(this))">
                                            <option value="">Select</option>
                                            @foreach($billing_profiles as $billing_profile)
                                                <option value='<?php echo htmlentities(json_encode($billing_profile), ENT_QUOTES, 'UTF-8'); ?>'>{{ $billing_profile['company_name'] }}</option>
                                            @endforeach
                                            {{--@foreach($billings as $billing)
                                                <option value="{{ $billing['Company_Name'] }}">{{ $billing['Company_Name'] }}</option>
                                            @endforeach--}}
                                        </select>
                                    </div>
                                </div>
                                <h6>Or.</h6>
                                <hr>
                                <h5>Register New Billing</h5>
                                <div class="form-group row">
                                    <label for="exampleInput" class="col-sm-4 col-form-label">Company Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="exampleInput" placeholder="Company Name" name="company_name[]">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="exampleInput" class="col-sm-4 col-form-label">Billing Email</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="exampleInput" placeholder="Billing Email" name="billing_email[]">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="exampleInput" class="col-sm-4 col-form-label">First Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="exampleInput" placeholder="First Name" name="first_name[]">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="exampleInput" class="col-sm-4 col-form-label">Last Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="exampleInput" placeholder="Last Name" name="last_name[]">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Payment Type</label>
                                    <div class="col-sm-4">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="payment_type[]" id="membershipRadios1" value="Credit_Card" checked="">
                                                Credit Card
                                                <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="payment_type[]" id="membershipRadios2" value="Bank_Transfer">
                                                Bank Transfer
                                                <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="checkbox col-sm-12 ">
                                        <label>
                                            <input type="checkbox" name="is_acknowledge[]" value="1"><i class="helper"></i> I hereby acknowledge and confirm that I have read, understood, and agree to all the terms and conditions set forth by Safelink. I fully accept the responsibility for adhering to these terms in all my interactions and activities associated with Safelink.
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7"></div>
                        </div>
                    </section>

                    <h3>Payment</h3>
                    <section>
                        <div id="payment_section">
                            <div class="loader-demo-box">
                                <div class="dot-opacity-loader">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <h5>Please wait...</h5>
                                </div>
                            </div>
                        </div>
                    </section>

                    <h3>Finish</h3>
                    <section>

                        <div class="message-body">

                            <div class="message-content">
                                <h4>Order Successfully Placed!</h4>
                                <p>Thank you for your purchase. We're thrilled to have you on board.</p>
                                <p>Our team is working hard on preparing your invoice. You can expect it to land in your inbox within the next 24 hours. Please ensure to check your Spam or Promotion folders if you do not see our email in your inbox.</p>
                                <p>Remember, we're here to help. If you need any assistance or have any questions, feel free to reach out.</p>
                                <p><a href="{{ url('/') }}" class="btn btn-primary me-2">Back to Homepage</a></p>
                            </div>

                        </div>
                    </section>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-popup-custom" class="modal " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title pl-2" id=""></h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-3">

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <style>
        .modal-header .close {
            margin-right: -10px !important;
            padding-top: 0px;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('constant.Google_APIKEY') }}&callback=initializeMap"></script>
    <script>
        function selected_product(obj) {
            var sec = obj.parents('.create_safelink_block').attr('data-sec');
            $('[data-sec="'+sec+'"]').find('.product-selected').removeClass('product-selected');
            $('[data-sec="'+sec+'"]').find('[name="prod[]"]').val(1);
            obj.toggleClass('product-selected');

            $('.modal-title').text(obj.data('product-name'));
            $('.modal-body').html('<img src="' +obj.find('img').attr('src')+'" alt="">');
            $('#modal-popup-custom').modal('show');
        }

        function add_more() {
            var blockHtml = $('.create_safelink_block').html();
            var prev = parseInt($('#sections').val());
            var sec = parseInt(prev) + parseInt(1);
            $('#sections').val(parseInt(sec))
            $('.more_blocks').append('<div class="create_safelink_block border p-4 mb-2" data-sec="'+sec+'">' + blockHtml + '</div>');
            $('[data-sec="'+sec+'"]').find('.product-selected').removeClass('product-selected');
            $('[data-sec="'+sec+'"]').find('#map_canvas_1').html('')
                .attr('id', 'map_canvas_' + sec);

            initialize(sec);
        }

        function prepare_table() {
            var safelink_id = '';
            var safelink_name = '';
            var country = '';
            var address = '';
            var lat = '';
            var long = '';
            var subscription_term = '';
            var subscription_term_text = '';
            var selected_device = '';
            var price = '';

            var prod_id = '';
            var imgsrc = '';
            var deviceimg = '';
            var total_price = 0;
            var vat_amount = 0;
            var gross_amount = 0;
            var single_prod_total_price = 0;
            var tabletr = '';
            var vat_percentage = 11;
            localStorage.removeItem('products');
            var products = [];
            $('.create_safelink_block').each(function(i, elem){
                safelink_id = $(elem).find('[name="safelink_id[]"]').val();
                safelink_name = $(elem).find('[name="safelink_name[]"]').val();
                if(safelink_id != "" && safelink_name != ""){
                    subscription_term = $(elem).find('[name="subscription_terms[]"]').val();
                    subscription_term_text = $(elem).find('[name="subscription_terms[]"] option:selected').text()
                    address = $(elem).find('[name="address[]"]').val();
                    country_id = $(elem).find('[name="country_id[]"]').val();
                    lat = $(elem).find('[name="lat[]"]').val();
                    long = $(elem).find('[name="long[]"]').val();

                    prod_id = $(elem).find('.product-selected').find('[name="prod_id[]"]').prop('value')
                    imgsrc = $(elem).find('.product-selected').find('[name="prod_img[]"]').prop('value')
                    deviceimg = '<img src="' + imgsrc + '" alt="device logo">'
                    selected_device = $(elem).find('.product-selected').find('[name="prod_name[]"]').prop('value')
                    price = parseInt($(elem).find('.product-selected').find('[name="prod_price[]"]').prop('value'));
                    single_prod_total_price = parseFloat(parseFloat(price) * parseInt(subscription_term));
                    tabletr += '<tr>\n' +
                        '                                        <td>' + safelink_id + '</td>\n' +
                        '                                        <td>' + safelink_name + '</td>\n' +
                        '                                        <td>' + subscription_term_text + ' </td>\n' +
                        '                                        <td>' + deviceimg+' '+ selected_device  + '</td>\n' +
                        '                                        <td>$' + parseFloat(price).toLocaleString() + '</td>\n' +
                        '                                        <td>$' + parseFloat(parseFloat(price) * parseInt(subscription_term)).toLocaleString() + '</td>\n' +
                        '                                    </tr>';
                    total_price = parseInt(total_price) + parseInt(single_prod_total_price);
                    var entry = {
                        prod_id : prod_id,
                        prod_name : selected_device,
                        prod_img_src : imgsrc,
                        prod_price : price,
                        single_prod_total_price : single_prod_total_price,
                        safelink_id : safelink_id,
                        safelink_name : safelink_name,
                        country_id : country_id,
                        address : address,
                        lat : lat,
                        long : long,
                        subscription_term : parseInt(subscription_term),
                    };
                    products.push(entry);
                    localStorage.setItem('products', JSON.stringify(products));
                }
            });



            vat_amount = parseFloat((parseFloat(total_price)*parseInt(vat_percentage))/100);

            tabletr += '<tr>\n' +
                '                                        <td></td>\n' +
                '                                        <td></td>\n' +
                '                                        <td></td>\n' +
                '                                        <td>Vat '+vat_percentage+'%</td>\n' +
                '                                        <td></td>\n' +
                '                                        <td>$' + vat_amount.toLocaleString() + '</td>\n' +
                '                                    </tr>';

            gross_amount = parseFloat(parseFloat(total_price) + parseFloat(vat_amount));
            tabletr += '<tr>\n' +
                '                                        <td></td>\n' +
                '                                        <td></td>\n' +
                '                                        <td></td>\n' +
                '                                        <td></td>\n' +
                '                                        <td>TOTAL</td>\n' +
                '                                        <td>$' + parseFloat(parseFloat(total_price) + parseFloat(vat_amount)).toLocaleString() + '</td>\n' +
                '                                    </tr>';

            var entry = {
                vat_percentage : vat_percentage,
                vat_amount : vat_amount,
                sub_amount : total_price,
                grand_total_amount : gross_amount,
            };
            localStorage.setItem('order_details', JSON.stringify(entry));
            $('#order_table').find('tbody').html('').append(tabletr)
        }

        function exist_billing(obj) {
            var $parent = $('.billing-info');
            if(obj.val() !== ""){
                $parent.find('[name="company_name[]"]').attr('disabled', true);
                $parent.find('[name="billing_email[]"]').attr('disabled', true);
                $parent.find('[name="first_name[]"]').attr('disabled', true);
                $parent.find('[name="last_name[]"]').attr('disabled', true);
            }else{
                $parent.find('[name="company_name[]"]').attr('disabled', false);
                $parent.find('[name="billing_email[]"]').attr('disabled', false);
                $parent.find('[name="first_name[]"]').attr('disabled', false);
                $parent.find('[name="last_name[]"]').attr('disabled', false);
            }
        }

        function prepare_order() {
            var exist_billing = '';
            var company_name = '';
            var billing_email = '';
            var first_name = '';
            var last_name = '';
            var payment_type = '';
            var is_acknowledge = 0;
            var $parent = $('.billing-info');
            exist_billing = $parent.find('[name="exist_billing[]"]').val()
            company_name = $parent.find('[name="company_name[]"]').val()
            billing_email = $parent.find('[name="billing_email[]"]').val()
            first_name = $parent.find('[name="first_name[]"]').val()
            last_name = $parent.find('[name="last_name[]"]').val()
            payment_type = $parent.find('[name="payment_type[]"]:checked').val();
            is_acknowledge = $parent.find('[name="is_acknowledge[]"]:checked').val();

            var entry = {
                user_id : '{{ Auth::id() }}',
                exist_billing : exist_billing != "" ? JSON.parse(exist_billing) : '',
                company_name : company_name,
                billing_email : billing_email,
                first_name : first_name,
                last_name : last_name,
                payment_type : payment_type,
                is_acknowledge : is_acknowledge,
            };
            localStorage.setItem('billing_details', JSON.stringify(entry));
            if(payment_type === 'Credit_Card'){
                ACFn.sendAjax('{{ route('getpayment') }}', 'post', {
                    products : localStorage.getItem('products'),
                    order_details : localStorage.getItem('order_details'),
                    billing_details : localStorage.getItem('billing_details'),
                }, $('#payment_section'))
            }else{
                setTimeout(function () {
                    $('[href="#next"]').trigger('click');
                    ACFn.sendAjax('{{ route('orderplaced') }}', 'post', {
                        products : localStorage.getItem('products'),
                        order_details : localStorage.getItem('order_details'),
                        billing_details : localStorage.getItem('billing_details'),
                    }, $('#payment_section'))
                },5000)

            }
        }

        function finish_process() {

                setTimeout(function () {
                    location.href = '{{ url('/billings') }}'
                },2000)

        }

        function validateStep(currentIndex, newIndex) {
            if (newIndex === 1) {
                var errorMessage = '';
                var selected_product_count = 0;

                $('.create_safelink_block').each(function(i, elem){
                    $(elem).find('.product-selected').length > 0 ? parseInt(selected_product_count) + parseInt(1) : 0;

                });

                /*if($('.create_safelink_block').find('.product-selected').length == 0){ console.log('here')
                    $('[data-sec="1"]').find('.row:first').addClass('has-error');
                    $('[data-sec="1"]').find('.row:first').append('<span class="error-block help-block">Please select at least one product</span>');

                    return false;
                }*/

                prepare_table();


            }
            if (newIndex === 2) {
                prepare_order();
            }

            if (newIndex === 3) {
                var $parent = $('.billing-info');
                var payment_type = $parent.find('[name="payment_type[]"]:checked').val();
                if(payment_type === 'Credit_Card') {
                    finish_process()
                }
            }
        }

        var geocoder;
        var map;
        var markersArray = [];

        function placeMarkerAndPanTo(latLng, map, pos) {
            var position = latLng;
            $('[data-sec="'+pos+'"]').find('[name="lat[]"]').val(position.lat());
            $('[data-sec="'+pos+'"]').find('[name="long[]"]').val(position.lng());
            console.log(position.lat(), position.lng());
            while (markersArray.length) {
                markersArray.pop().setMap(null);
            }
            var marker = new google.maps.Marker({
                draggable: true,
                position: latLng,
                map: map,
                title: "Select Your Location!"
            });
            map.panTo(latLng);

            markersArray.push(marker);

            google.maps.event.addListener(marker, 'dragend', function(event) {
                var position = event.latLng;
                $('[data-sec="'+pos+'"]').find('[name="lat[]"]').val(position.lat());
                $('[data-sec="'+pos+'"]').find('[name="long[]"]').val(position.lng());
                console.log(event);
            });
        }

        function initialize(pos) {
            console.log('pos-', pos);
            var map = new google.maps.Map(
                document.getElementById("map_canvas_" + pos), {
                    center: new google.maps.LatLng(-6.2253131,106.8195442),
                    zoom: 13,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

            map.addListener('click', function(e) {
                var position = e.latLng;
                placeMarkerAndPanTo(position, map, pos);
            });

        }


        google.maps.event.addDomListener(window, "load", function(event) {
            initialize(1);
        });

        $(document).ready(function () {

            var form = $("#contact");
            form.validate({
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                    console.log('error---',error)
                },
                /*rules: {
                    confirm: {
                        equalTo: "#password"
                    }
                }*/
            });
            form.children("div").steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex) {
                    form.validate().settings.ignore = ":disabled";
                    console.log('form.valid()---',form.valid());
                    validateStep(currentIndex, newIndex)
                    return form.valid();
                },
                onFinishing: function (event, currentIndex) {
                    form.validate().settings.ignore = ":disabled";
                    return form.valid();
                },
                onFinished: function (event, currentIndex) {
                    finish_process();
                }
            });

            ACFn.bank_transfer_order_placed = function (F, R) {
                if(R.success){
                    ACFn.show_message(F, R);
                }
            }
        });
    </script>




@stop
