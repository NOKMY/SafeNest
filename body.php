
<script src="https://www.gstatic.com/firebasejs/9.9.3/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.9.3/firebase-database.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="content container-fluid">

    <div class="row">
    <div class="col-xl-4 col-sm-4 col-12">
            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon bg-primary">
                            <i class="fi fi-rr-cloud-sun-rain"></i>
                        </span>
                        <div class="dash-count">
                            <a href="" class="count-title">Water Status</a>
                            <a href="" class="count "> <span id="waterLevelStatus"></span></a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-4 col-12">
            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon bg-primary">
                            <i class="fe fe-users"></i>
                        </span>
                        <div class="dash-count">
                            <a href="#" class="count-title">Users</a>
                            <a href="#" class="count">{{list_user}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-4 col-12">
            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon bg-primary">
                            <i class="fe fe-clock"></i>
                        </span>
                        <div class="dash-count p-1">
                            <a href="#" class="count-title">Time</a>
                            <a href="" class="btn btn-primary">{{current_datetime_with_tz}}</a>
                        </div>
                        
                    </div>
                </div>
            </div>

    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between p-1">
                            <h4></h4>
                            <div class="">
                              <button onclick="printChart()" class="btn btn-success">Print Chart</button>
                            </div>                          
                          </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-10">
                                <canvas id="waterLevelChart"></canvas>
                            </div>
                            <!-- <div class="col-sm-2 border-start">
                                <form method="post" action="">
                                    {% csrf_token %}
                                    <div class="row">
                                        {% for field in form %}
                                            <div class="col-4 col-sm-12">
                                                {{ field.label_tag }}
                                                {{ field|add_class:'form-control' }}
                                                {{ field.errors }}
                                            </div>
                                        {% endfor %}
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3" id="updateButton">Update</button>
                                </form>
                            </div> -->
                            <pre id="historicalRecords"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    