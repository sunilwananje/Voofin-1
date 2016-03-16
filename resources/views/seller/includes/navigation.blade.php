        <!-- Top Nav Start -->
        <div id='cssmenu'>
          <ul>
            <li><a href='#'> <i class="fa fa-file-text-o"></i> Purchase Orders</a>
             <ul>
              <li><a href='{{URL::route('seller.poListing.view')}}'>Purchase Orders</a></li>
            </ul>
          </li>
          <li><a href='#'> <i class="fa fa-file-text-o"></i> Invoices</a>
           <ul>
            <li><a href='{{URL::route('seller.invoice.view')}}'>Invoices</a></li>
            <li><a href='{{URL::route('seller.invoice.add')}}'>Create Invoice</a></li>
          </ul>
        </li>
        <li><a href='#'> <i class="fa fa-tag"></i> I-Discounting</a>
         <ul>
		  <li><a href='{{URL::route('seller.iDiscounting.view')}}'> I-Discounting</a></li>
          <li><a href='{{URL::route('seller.piListing.view')}}'>Payment Instruction</a></li>
        </ul>
      </li>
      
      <li><a href='#'><i class="fa fa-credit-card"></i>Payments</a>
        <ul>
          <li><a href='{{URL::route('seller.remittances.view')}}'>Remittances</a></li>
        </ul>
      </li>
      
      <li><a href='#'><i class="fa fa-pencil-square-o"></i>Reports</a>
          <ul>
              <li><a href='{{URL::route('seller.reports.inwardRemittanceReportListing.view')}}'>Inward Remittance Report</a></li>
              <li><a href='{{URL::route('seller.reports.sellerDiscountingUsageReportListing.view')}}'>Discounting Usage Report</a></li>
              <li><a href='{{URL::route('seller.reports.sellerDiscountingReportListing.view')}}'>Seller Discounting Report</a></li>
          </ul>
      </li>
      
      <li><a href='#'><i class="fa fa-cog"></i>Configurations</a>
        <ul>
          <li><a href='{{URL::route('seller.sellerConfiguration.view')}}'>Configurations</a></li>
          <li><a href='#'>Auto Discounting</a></li>
          <li class="has-sub"><span class="submenu-button"></span><a href="#" data-original-title="" title="">Masters</a>
            <ul>
              <li><a href="{{URL::route('seller.user.view')}}">Seller Users</a></li>
              <li><a href="{{URL::route('seller.role.view')}}">Seller Roles</a></li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </div>
  
  <!-- Top Nav End -->

  