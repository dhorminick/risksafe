    <footer class='main-footer custom c'>
        <div class='row custom-row'>
            <div class='top_footer col-12'>
                <a href='/admin/' class='_logo'>RISKSAFE</a>
                <div class='a'>
                    <a href='/risksafe-help'>HELP</a>
                    <a href='#'>PRIVACY POLICY</a>
                    <a href='#'>TERMS OF USE</a>
                </div>
            </div>
            <hr />
            <div class='col-12 bottom_footer'>
                &copy; RiskSafe - <?php echo date("Y"); ?>
            </div>
        </div>
    </footer>
    
    <style>
        ._logo{
            color:var(--custom-primary);
            font-size:30px;
            font-weight:bolder;
        }
        .top_footer{
            display:flex;
            align-items:center;
            justify-content: space-between;
            gap:20px;
        }
        .top_footer .a{
            display:flex;
            align-items:center;
            gap:15px;
        }
        .bottom_footer{
            text-align:right;
        }
        .bottom_footer div{
            width: max-content;
        }
        @media (max-width: 768px) {
            .bottom_footer div{
                width: 100%;
            }
        }
    </style>