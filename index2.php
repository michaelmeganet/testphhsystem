<?php
//to check debug $_POST
?>
<!DOCTYPE html>
<html lang="en">

    <?php include "header.php"; ?>

    <body>

        <?php #include"navmenu.php"; ?>

        <div class="container">

            <div class="page-header" id="banner">
                <div class="row">
                    <div class="col-lg-8 col-md-7 col-sm-6">
                        <h1>INTERMEDIATE JOBLIST</h1>
                    </div>
                    <div class="col-lg-4 col-md-5 col-sm-6">
                        <div class="sponsor">
                          <!-- <script async type="text/javascript" src="//cdn.carbonads.com/carbon.js?serve=CKYIE23N&placement=bootswatchcom" id="_carbonads_js"></script> -->
                        </div>
                    </div>
                </div>
            </div>
            <section id='tabs'>
                <div class='container' id="mainArea">
                    <p class="lead">Issue Quotation</p>
                    <div class='row'>
                        <div class='col-md'> 
                            <!--  modal area  -->
                            <button ref="intModalButton" id="intModalButton" data-toggle="modal" data-target="#intModal" data-backdrop="static" hidden></button>
                            <div class="modal fade  bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="intModal">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class='modal-header'>
                                            <h5 class="modal-title">Generate Intermediate Joblist</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                            <div class='row'>
                                                <div class='col-md-4'>
                                                    <label class='custom-label'>Material</label><br>
                                                    <input type='text' class='form-control' readonly v-bind:value='schDetail.grade'/>
                                                </div>
                                                <div class='col-md-4'>
                                                    <label class='custom-label'>Quantity</label><br>
                                                    <input type='text' class='form-control'  v-model='int_data.quantity'/>
                                                </div>
                                            </div>
                                            <br>
                                            <div class='row'>
                                                <div class='col-md'>
                                                    <label class="control-label"><h5>Dimension</h5></label>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='col-md-4'>
                                                    <label class="control-label">MDT</label>
                                                </div>
                                                <div class='col-md-4'>
                                                    <label class="control-label">MDW</label>
                                                </div>
                                                <div class='col-md-4'>
                                                    <label class="control-label">MDL</label>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='col-md-4'>
                                                    <input type='text' class='form-control' v-model='int_data.mdt' />
                                                </div>
                                                <div class='col-md-4'>
                                                    <input type='text' class='form-control' v-model='int_data.mdw' />
                                                </div>
                                                <div class='col-md-4'>
                                                    <input type='text' class='form-control' v-model='int_data.mdl' />
                                                </div>
                                            </div>
                                            <br>
                                            <div class='row'>
                                                <div class='col-md'>
                                                    <label class="control-label"><h5>Finishing Dimension</h5></label>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='col-md-4'>
                                                    <label class="control-label">FDT</label>
                                                </div>
                                                <div class='col-md-4'>
                                                    <label class="control-label">FDW</label>
                                                </div>
                                                <div class='col-md-4'>
                                                    <label class="control-label">FDL</label>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='col-md-4'>
                                                    <input type='text' class='form-control' v-model='int_data.fdt' />
                                                </div>
                                                <div class='col-md-4'>
                                                    <input type='text' class='form-control' v-model='int_data.fdw' />
                                                </div>
                                                <div class='col-md-4'>
                                                    <input type='text' class='form-control' v-model='int_data.fdl' />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-success" @click="generateIntermediateJL()">Submit</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end modal area-->
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="row no-gutters">
                                <div class="col-md">
                                    Please Scan Joblist
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <div class="col-md">
                                    <input type="text" class="form-control" v-model="jobcode" v-on:keyup.enter="parseJobCode()"/>
                                </div>
                            </div>

                            <div class="row no-gutters">
                                <div class="col-md">
                                    <label class="custom-label" v-html="jobcode_response">{{jobcode_response}}</label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <br>
                    <br>
                    <div class='container'>
                        <div class="row">
                            <div class="col-md" v-if="joblist_response_status == 'error'">
                                <label class="custom-label" v-html="joblist_response">{{joblist_response}}</label>
                            </div>
                            <div class="col-md" v-else-if='schDetail != ""'>
                                <div class='row no-gutters'>
                                    <div class='col-md-1'>
                                        <label class='custom-label'>Quono</label>
                                    </div>
                                    <div class='col-md'>
                                        <label>: {{schDetail.quono}}</label>
                                    </div>
                                </div>
                                <div class='row no-gutters'>
                                    <div class='col-md-1'>
                                        <label class='custom-label'>CID</label>
                                    </div>
                                    <div class='col-md'>
                                        <label>: {{schDetail.cid}}</label>
                                    </div>
                                </div>
                                <div class='row no-gutters'>
                                    <div class='col-md-1'>
                                        <label class='custom-label'>Company</label>
                                    </div>
                                    <div class='col-md'>
                                        <label>: {{schDetail.company}}</label>
                                    </div>
                                </div>
                                <div class='row no-gutters'>
                                    <div class='col-md-1'>
                                        <label class='custom-label'>Date Issue</label>
                                    </div>
                                    <div class='col-md'>
                                        <label>: {{schDetail.date_issue}}</label>
                                    </div>
                                </div>
                                <div class='row no-gutters'>
                                    <div class='col-md-1'>
                                        <label class='custom-label'>Job Status</label>
                                    </div>
                                    <div class='col-md'>
                                        <label style='color:yellow'>: {{schDetail.status}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='container-fluid no-gutters' v-if='joblist_response_status != "error" && schDetail!= ""'>
                        <div class='row'>
                            <div class='col-md-4'>
                                <label class='custom-label'>Material</label><br>
                                <input type='text' class='form-control' readonly v-bind:value='schDetail.grade'/>
                            </div>
                            <div class='col-md-4'>
                                <label class='custom-label'>Quantity</label><br>
                                <input type='text' class='form-control' readonly v-bind:value='schDetail.quantity'/>
                            </div>
                        </div>
                        <br>
                        <div class='row'>
                            <div class='col-md'>
                                <label class="control-label"><h5>Dimension</h5></label>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-4'>
                                <label class="control-label">MDT</label>
                            </div>
                            <div class='col-md-4'>
                                <label class="control-label">MDW</label>
                            </div>
                            <div class='col-md-4'>
                                <label class="control-label">MDL</label>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-4'>
                                <input type='text' class='form-control' v-bind:value='schDetail.mdt' readonly/>
                            </div>
                            <div class='col-md-4'>
                                <input type='text' class='form-control' v-bind:value='schDetail.mdw' readonly/>
                            </div>
                            <div class='col-md-4'>
                                <input type='text' class='form-control' v-bind:value='schDetail.mdl' readonly/>
                            </div>
                        </div>
                        <br>
                        <div class='row'>
                            <div class='col-md'>
                                <label class="control-label"><h5>Finishing Dimension</h5></label>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-4'>
                                <label class="control-label">FDT</label>
                            </div>
                            <div class='col-md-4'>
                                <label class="control-label">FDW</label>
                            </div>
                            <div class='col-md-4'>
                                <label class="control-label">FDL</label>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-4'>
                                <input type='text' class='form-control' v-bind:value='schDetail.fdt' readonly/>
                            </div>
                            <div class='col-md-4'>
                                <input type='text' class='form-control' v-bind:value='schDetail.fdw' readonly/>
                            </div>
                            <div class='col-md-4'>
                                <input type='text' class='form-control' v-bind:value='schDetail.fdl' readonly/>
                            </div>
                        </div>
                        <br>
                        <div class='row'>
                            <div class='col-md'>
                                <button type='button' class='btn btn-info btn-block' v-if='schDetail.status != "cancelled"' @click="propIntermediateData();">Generate Intermediate Joblist</button>
                                <button type='button' class='btn btn-outline-info btn-block' v-else-if='schDetail.status == "cancelled"' disabled>Job is Cancelled</button>
                            </div>
                        </div>
                    </div>

                </div> 
            </section>


        </div>
        <?php  include"footer.php" ?>
        <script>
            var ijVue = new Vue({
                el: '#mainArea',
                data: {
                    phpajaxresponsefile: 'intJL.axios.php',
                    jobcode: '',
                    jobcode_response: '',
                    jobcode_response_status: '',
                    parsedJobCode: '',
                    schDetail: '',
                    schPeriod: '',
                    qid: '',
                    quono: '',
                    joblist_response: '',
                    joblist_response_status: '',
                    int_data: {
                        quantity: 0,
                        mdt: 0,
                        mdw: 0,
                        mdl: 0,
                        fdt: 0,
                        fdw: 0,
                        fdl: 0
                    }
                },
                watch: {

                },
                computed: {

                },
                methods: {
                    parseJobCode: function () {
                        console.log('on parseJobcode...');
                        let jc = this.jobcode;
                        axios.post(this.phpajaxresponsefile, {
                            action: 'parseJobCode',
                            jobcode: jc
                        }).then(function (rp) {
                            console.log(rp.data);
                            ijVue.jobcode_response_status = rp.data.status;
                            if (rp.data.status === 'ok') {
                                ijVue.parsedJobCode = rp.data.msg;
                                ijVue.jobcode_response = '<font style="color:#37ff00">' + rp.data.msg + '</font>';
                            } else {
                                ijVue.jobcode_response = '<font style="color:red">' + rp.data.msg + '</font>'
                            }
                            return rp.data.status;
                        }).then(function (status) {
                            if (status === 'ok') {
                                ijVue.getJoblistDetail(ijVue.parsedJobCode);
                            }
                        })
                    },
                    getJoblistDetail: function (jobcode) {
                        console.log('on getJoblistDetal');
                        axios.post(this.phpajaxresponsefile, {
                            action: 'getJoblistDetail',
                            jobcode: jobcode
                        }).then(function (rp) {
                            console.log(rp.data);
                            ijVue.joblist_response_status = rp.data.status;
                            if (rp.data.status == 'ok') {
                                ijVue.schDetail = rp.data.schDetail;
                                ijVue.qid = rp.data.schDetail.qid;
                                ijVue.quono = rp.data.schDetail.quono;
                                ijVue.schPeriod =rp.data.schPeriod;
                            } else {
                                ijVue.joblist_response = rp.data.msg;
                            }
                        })
                    },
                    propIntermediateData: function () {
                        this.int_data.quantity = this.schDetail.quantity;
                        this.int_data.mdt = this.schDetail.mdt;
                        this.int_data.mdw = this.schDetail.mdw;
                        this.int_data.mdl = this.schDetail.mdl;
                        this.int_data.fdt = this.schDetail.fdt;
                        this.int_data.fdw = this.schDetail.fdw;
                        this.int_data.fdl = this.schDetail.fdl;

                        this.$refs['intModalButton'].click();
                    },
                    generateIntermediateJL:  function() {
                        let qid = this.qid;
                        let intData = this.int_data;
                        let quono = this.quono;
                        axios.post(this.phpajaxresponsefile,{
                            action: 'generateIntermediateJL',
                            qid: qid,
                            quono: quono,
                            origin_period : this.schPeriod,
                            jobcode: this.parsedJobCode,
                            intData: intData
                        }).then(function(rp){
                            console.log('on generateIntermediateJL ...');
                            console.log(rp.data);
                        });
                    }
                }
            })
        </script>
    </body>
</html>


