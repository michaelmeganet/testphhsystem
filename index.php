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
                    <div class="col-lg-12 col-md-9 col-sm-8">
                        <h1>CHECK DOUBLE JOBLIST RECORDS IN 2102 AND 2103</h1>
                        <br>
                        <br>
                        <?php
                        include("getjob.php");
                        ?>
                    </div>

                </div>
            </div>



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


