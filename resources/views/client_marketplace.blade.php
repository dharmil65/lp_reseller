@extends ('layouts.client_index')

@section('content')
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        .logout-container {
            text-align: right;
            margin: 20px;
        }
        .logout-btn {
            background-color: #d9534f;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .logout-btn:hover {
            background-color: #c9302c;
        }
        .site-header {
            background: #275570;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .site-header .side-logo a img {
            max-width: 200px;
            padding: 0 15px;
        }

        .site-header .main-navigation ul {
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .site-header .main-navigation ul li {
            padding: 0;
            opacity: 0.8;
            font-size: 15px;
            text-transform: capitalize;
            font-weight: 300;
            letter-spacing: 1px;
            color: #fff;
        }

        .site-header .main-navigation ul li a {
            color: #Ffff;
            text-decoration: none;
        }

        .marketplace-header .menu-icon .menu-icon-detail {
            display: flex;
            align-items: center;
            margin-bottom: 0;
            justify-content: flex-end;
            padding-left: 40px;
            list-style: none;
        }

        .marketplace-header .menu-icon li a {
            padding: 0 20px;
            position: relative;
            cursor: pointer;
        }

        .marketplace-header .menu-icon li a .notification-number {
            position: absolute;
            top: -10px;
            right: 6px;
            background: #fd6a3a;
            border-radius: 6px;
            color: #fff;
            font-size: 10px;
            text-align: center;
            min-width: 20px;
            height: 20px;
            line-height: 20px;
            padding: 2px;
        }

        .marketplace-header .profile-wrapper {
            border: 1px solid #fff;
            border-radius: 20px;
            position: relative;
        }

        .marketplace-header .menu-icon li a {
            padding: 0 20px;
            position: relative;
            cursor: pointer;
            color: #fff;
        }

        .marketplace-header .profile-wrapper a img {
            padding-right: 5px;
            max-width: 45px;
            height: 40px;
            border-radius: 50%;
        }

        .profile-wrapper .dropdown-toggle::after {
            position: absolute;
            top: 18px;
        }

        .marketplace-header .profile-wrapper .dropdown-menu {
            position: absolute;
            right: 0;
            top: 53px;
            border-radius: 10px;
            z-index: 9999;
            font-size: 16px;
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, .15);
            padding: 0;
            width: 160px;
            display: none;
        }

        .marketplace-header .profile-wrapper .dropdown-menu .dropdown-item {
            padding-left: 15px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .marketplace-header .profile-wrapper .dropdown-menu a {
            color: #275570;
            padding: 0;
            font-size: 14px;
            position: relative;
            cursor: pointer;
        }

        .profile-wrapper .dropdown-item a img {
            padding-right: 10px;
            max-width: 35px;
            height: auto;
            border-radius: 50%;
        }

        .marketplace-header li.profile-wrapper a {
            padding: 0 20px 0 0;
        }

        .marketplace-wrapper {
            padding: 0 15px;
            display: flex;
            align-items: baseline;
            gap: 0 10px;
        }  

        .marketplace-wrapper .side-wrapper {
            width: 100%;
            padding-left: 0;
        }

        .marketplace-wrapper .marketplace-details {
            width: 80%;
        }

        .marketplace-header .profile-wrapper .dropdown-menu.show {
            display: block;
        }

        .side-logo {
            display: flex;
            align-items: center;
            gap: 0 11px;
        }

        .side-logo h5 {
            margin-bottom: 0;
            color: #fff;
            font-size: 30px;
            font-weight: 700;
        }

        .marketplace-table table th {
            white-space: normal;
            word-break: break-word;
            line-height: 1.2;
        }

        .marketplace-table table td {
            text-align: center;
        }

        .marketplace-table table.dataTable thead .sorting,
        .marketplace-table table.dataTable thead .sorting_asc {
            background-image: none !important;
        }

        .table-detail td .cart_btn {
            min-width: 110px;
        }

        .table-header th:first-child, .table-detail td:first-child {
            width: auto !important;
            word-break: break-word;
        }

        .cart_wishlist_cta {
            width: 80px !important;
        }

        .website strong {
            color: #f2652d;
        }
    </style>

    <div class="site-wrapper">
        <section class="marketplace-wrapper">
            <div class="row">
                <div class="col-md-12 col-lg-2 p-0">
                    <aside class="side-wrapper">
                        <div class="side-content">
                            <div id="accordion" class="filter-wrapper">
                                <div class="filter">
                                    <div class="filter-icon">
                                        <span><img src="https://lp-latest.elsnerdev.com/assets/latest_assets_new/images/marketplace-filter.png">Filters</span>
                                    </div>
                                    <div class="filter-btn">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div class="filter-detail d-none">
                                        <a href="https://lp-latest.elsnerdev.com/advertiser/marketplace" id="filterClear">Clear All</a>
                                    </div>
                                </div>
                                
                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <a class="card-link collapsed cart-link-icon" data-toggle="collapse" href="#collapseOne">
                                            Moz DA
                                        </a>
                                    </div>
                                    <div id="collapseOne" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                <div class="min-form">
                                                    <!-- <h6>Custom Range</h6> -->
                                                    <input type="text" name="moz_da numeric" id="min_moz_da" class="form-control numeric" placeholder="min" autocomplete="off" maxlength="8">
                                                    <input type="text" name="moz_da numeric" id="max_moz_da" class="form-control numeric" placeholder="max" autocomplete="off" maxlength="8">
                                                    <button class="btn button btn-primary daminmax" type="button" disabled="">GO</button>
                                                    <p id="da_msg" style="display: none; color:red;"><b>Enter valid value</b></p>
                                                    <p id="da_msg1" style="display: none; color:red;"><b>Moz DA must be 0 to 100</b></p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="category-filter">
                                    <h6>Switch to <span data-title="Forbidden Category">FC</span></h6>
                                    <label class="switch">
                                        <input type="checkbox" id="categoryfilter_check" class="" value="0" name="vacationMode">
                                        <div class="slider round"></div>
                                    </label>
                                </div> -->
                                <div class="card ">
                                    <div class="card-header category-filter">
                                        <h6>Category</h6>
                                        <label class="switch">
                                            <input type="checkbox" id="categoryfilter_check" class="" value="0" name="vacationMode">
                                            <div class="slider round"></div>
                                        </label>    
                                        <h6>FC <span class="order-tooltip" info-title="FC means Forbidden Category which covers topic related to Casino, Cryptocurrency, CBD, Sports Betting, Vape and Rehabilitation."> <img src="https://lp-latest.elsnerdev.com/assets/latest_assets_new/images/content-form-icon.png" alt="content-form-icon"></span></h6>        
                                        <!-- <a class="card-link">
                                            Category
                                        </a> -->                
                                    </div>
                                    <div id="collapseeight" data-parent="#accordion">
                                        <div class="card-body">
                                                                                        <div class="load-more category top" style="display:none">
                                                <a href="#category" class="search_categories_loadmore loadMoreBtn" style="pointer-events:auto;" data-toggle="collapse">Show more</a>
                                            </div>
                                                                                                                                    <form id="categoryForm" style="display: block;">
                                                <div class="form-group">
                                                    <input type="search" placeholder="Search Category" onselectstart="return true" class="category-search" id="search_categry_input" autocomplete="off">
                                                </div>
                                                <div id="first_two_category"><div class="form-group" data-no="1">
                                                    <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="SaaS" id="SaaS" value="SaaS">
                                                    <label for="SaaS"><span class="saas-img-cate">SaaS🔥  </span></label>
                                                </div><div class="form-group" data-no="2">
                                                    <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="land,agriculture,farming,herb&amp;spices,Agronomy,Agribusiness,Crops,Livestock,Soil,Irrigation,Organic farming,Harvesting,Pesticides,Fertilizers,Dairy farming,Poultry,Greenhouse,Farmer" id="Agriculture" value="Agriculture">
                                                    <label for="Agriculture"><span class="saas-img-cate">Agriculture</span></label>
                                                </div></div>
                                                <div id="category" class="collapse load_more_data"><div class="form-group" data-no="3">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Animals&amp;Pets,pet,dog,cat,animal,birds,Pet,petproducts,animals,pets,Breed,Petcare,Pethealth,Petnutrition,Petgrooming,Exoticpets,Smallanimal,Reptiles,Animalrescue,Animalshelter,Wildlife,Petlover,Veterinarian" id="category_Animals &amp; Pets" value="Animals &amp; Pets">
                                                        <label for="category_Animals &amp; Pets">
                                                            <span class="saas-img-cate">
                                                                Animals &amp; Pets                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="4">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Weapon,bombsorrockets,shot,shrapnel,bullets,orshellsfiredbyguns.,Firearms,Guns,Ammunition,Rifles,Pistols,Shells,Cartridges,Explosives,Grenades,Missiles,Artillery,Mortars,Machineguns,Shotguns,Revolvers,Gunpowder,Ballistics,Gunfire,Firearm parts,Gun accessories,Gun storage,Gun laws,Shooting ranges" id="category_Arms and ammunition" value="Arms and ammunition">
                                                        <label for="category_Arms and ammunition">
                                                            <span class="saas-img-cate">
                                                                Arms and ammunition                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="5">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Arts&amp;Entertainment,craft,celebrities,movies,diy,cinema,art,design,films,TVshows,decoration,giftideas,cartoon,freeprintables,homediy,Entertainment,Painting,gift,Dance,Theater,Performer,Stage,Acting,Plays,Drama,Comedy,Stand-up comedy,Exhibitions,Visual arts,Sculpture,Drawing,Sketching,Illustration,Crafts,DIY projects,Handmade,Craft supplies,Artisan,Artwork,Entertainment news,Celebrity,Movie previews,Film reviews,TV series,TV networks,TV channels,Streaming,Movie industry,TV industry,Hollywood,Bollywood,Cinema history,Box office,Film festivals,Award shows,Paparazzi,Entertainment industry,Show business,Drawing.,Anime" id="category_Arts &amp; Entertainment" value="Arts &amp; Entertainment">
                                                        <label for="category_Arts &amp; Entertainment">
                                                            <span class="saas-img-cate">
                                                                Arts &amp; Entertainment                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="6">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Automobiles,car,bikes,auto,robotics,vehicles,Carreviews,cycling,Transpotations,vegan,Automotive,Auto,Motorcycles,Trucks,SUVs,Vans,Electric vehicles,Hybrid vehicles,Roads,Driverless vehicles,Autonomous vehicles,Convertibles,Sedans,Roadsters,Scooters,Mopeds,Bicycles,Electric bikes,Accident,Road accident." id="category_Automobiles" value="Automobiles">
                                                        <label for="category_Automobiles">
                                                            <span class="saas-img-cate">
                                                                Automobiles                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="7">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="beauty,skincare,makeup,haircare,nailcare,beautyremedies,Cosmetics,Fragrance,Perfume,Beauty products,Skincare tips,Anti-aging,Facial,Body care,Spa,Wellness,Therapy,Regimen,Skincare ingredients,Hairstyles,Nail polish,Manicure,Pedicure,Natural beauty." id="category_Beauty" value="Beauty">
                                                        <label for="category_Beauty">
                                                            <span class="saas-img-cate">
                                                                Beauty                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="8">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="blog,blogging,post,writeblog,Writeforus,Ghostwriter" id="category_Blogging" value="Blogging">
                                                        <label for="category_Blogging">
                                                            <span class="saas-img-cate">
                                                                Blogging                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="9">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Business,meeting,business,retails,startup,enterprise,Companies,entreprenuer,Franchising,conference,Industry,management,Entrepreneurship,Strategy,Management,Leadership,Growth,Innovation,Consulting,Analysis,Planning,Operations,Investment,Insurance,Consultant,Advisor,Partnership,Collaboration,Performance,Productivity,Workflow,Intelligence,Analytics,Seminar,Conference,Webinar,Forum,Community,Guidance,Service,Product,Opportunity,Challenge,Risk,Ethics,Values,Culture,Brand,Reputation,Growth." id="category_Business" value="Business">
                                                        <label for="category_Business">
                                                            <span class="saas-img-cate">
                                                                Business                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="10">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Career&amp;Employment,job,employment,resume,cv,career,services,interviews,leadership,personaldevelopment,Selfimprovement,office,Training,Employees,Portfolio,Success,learning,Recruitment,Hiring,Job seeker,Job openings,HR,Job applications,Human resources,Employee benefits,Performance appraisal,Career advancement,Professional development,Skill development,Skill building,Wage,Hike,Work-life,Resume,Staff,Interview." id="category_Career &amp; Employment" value="Career &amp; Employment">
                                                        <label for="category_Career &amp; Employment">
                                                            <span class="saas-img-cate">
                                                                Career &amp; Employment                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="11">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Computer&amp;Electronics,gadget,smartphone,TV,laptops,phone,mobile,PC,Tablets,computer,electronics,drones,smartwatches,Mobile,Tech gadgets,Electronic devices,Computing devices,Equipment,Visual technology,Wearable tech,Gaming,Networking devices,Peripherals,Computer hardware,Cybersecurity,Data storage,Artificial intelligence,Machine learning,Robotics,Automation technology,Smart devices,Digital cameras,Headphones,Speaker systems,Projectors,Printers,Scanners,Monitors,Keyboards,Mice,Audio,Video,Chargers,Circuit boards,Microcontrollers,Sensors,Actuators,Display screens,Batteries,Power banks,Cables,Connectors,USB devices,Wireless devices,Bluetooth,Wi-Fi technology,Broadband technology,Satellite communication,5G technology,Health trackers,Smart TVs,Streaming devices,Media players,DVD players." id="category_Computer &amp; Electronics" value="Computer &amp; Electronics">
                                                        <label for="category_Computer &amp; Electronics">
                                                            <span class="saas-img-cate">
                                                                Computer &amp; Electronics                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="12">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="cashbacks,Coupons,Offers,deals,CouponsOffers&amp;Cashback,Discounts,Savings,Vouchers,Promotions,Bargains,Rebates,Promo Codes,Specials,Rewards,Promo Deals,Discount Codes,Cashback Offers,Discounted Prices,Bonus Points,Coupon Codes,Gift Cards,Shopping Rewards,Holiday Promotions,Price Drops,Price Markdowns,Clearance Discounts,Sign-up Bonuses,Daily Deals,Freebies,Year-End Sales,Spring Sales" id="category_Coupons Offers &amp; Cashback" value="Coupons Offers &amp; Cashback">
                                                        <label for="category_Coupons Offers &amp; Cashback">
                                                            <span class="saas-img-cate">
                                                                Coupons Offers &amp; Cashback                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="13">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="DigitalMarketing,SEO,digital,OnlineMarketing,InternetMarketing,SocialMediaMarketing(SMM),ContentMarketing,EmailMarketing,PayPerClick(PPC)Advertising,DigitalAdvertising,SearchEngineMarketing(SEM),WebAnalytics,DigitalStrategy,Influencer,Affiliate,VideoMarketing,MobileMarketing,E-commerce,GrowthHac,Branding,SEO SearchEngineOptimization,On-PageSEO,Off-PageSEO,TechnicalSEO,LocalSEO,OrganicSearch,KeywordResearch,LinkBuilding,SEOAudit,SERP(SearchEngineResultsPage),Backlinks,AnchorText,MetaTags,SchemaMarkup,GoogleAnalytics,GoogleSearchConsole,SEOTools,WhiteHatSEO,BlackHatSEO" id="category_Digital Marketing" value="Digital Marketing">
                                                        <label for="category_Digital Marketing">
                                                            <span class="saas-img-cate">
                                                                Digital Marketing                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="14">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Online Retail,Digital Commerce,Internet Shopping,Web Store,Virtual Marketplace,Online Marketplace,E-commerce Platform,Digital Storefront,Vendor,Online Buy,Online Sell" id="category_Ecommerce" value="Ecommerce">
                                                        <label for="category_Ecommerce">
                                                            <span class="saas-img-cate">
                                                                Ecommerce                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="15">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Education,tutorial,edu,Courses,podcasts,hostel,School,Student,Learning,Academics,Study,Online learning,Distance learning,Study materials,Courses,Podcasts,Classroom,Teaching,Homework,Workshops,Conferences,Degree,Library." id="category_Education" value="Education">
                                                        <label for="category_Education">
                                                            <span class="saas-img-cate">
                                                                Education                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="16">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="nature,environment,weather,climate,Ecology,Conservation,Sustainability,Biodiversity,Wildlife,Natural resources,Pollution,Water conservation,Ecosystems,Green living,Climate,Eco-friendly,Waste management,Recycling,Composting,Eco-tourism,Natural disasters." id="category_Environment" value="Environment">
                                                        <label for="category_Environment">
                                                            <span class="saas-img-cate">
                                                                Environment                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="17">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Family,relationship,parenting,Baby,pregnancy,peoples,kids,child,love,living,dating,mom'scorner,kid'scorner,Fatherhood,Motherhood,Mom,Children,Mommy,MommySide,Siblings,Grandparents,Extended family,In-laws,Parenting tips,Child development,Toddler,Adolescence,Teenagers,Parenting styles,Parent-child relationship,Single parenting,Co-parenting,Newborn,Baby milestones,Child discipline,Positive parenting,Parenting challenges." id="category_Family" value="Family">
                                                        <label for="category_Family">
                                                            <span class="saas-img-cate">
                                                                Family                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="18">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Fashion&amp;Lifestyle,Nails,beauty,Hairstyles,Lifestyle,Women,Style,Fashion,MakeUp,Party,SelfCare,Skin,Model,Accessories,Fashion trends,Outfit ideas,Clothing,Apparel,Fashion accessories,Jewelry,Watches,Handbags,Shoes,Footwear,Street style,Fashion influencers,Lifestyle tips." id="category_Fashion &amp; Lifestyle" value="Fashion &amp; Lifestyle">
                                                        <label for="category_Fashion &amp; Lifestyle">
                                                            <span class="saas-img-cate">
                                                                Fashion &amp; Lifestyle                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="19">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Finance,Credit,Finance,Investment,Economy,Invest,Banking,Loan,CreditScores,Saving,Taxes,insurance,Budgeting,Retirement,Asset,Portfolio Management,Pension Plans,Mutual Funds,Stocks,Bonds,Securities,Diversification,Commodities,Derivatives,ForexTrading,Angel Investing,Crowdfunding,Capital,Credit" id="category_Finance" value="Finance">
                                                        <label for="category_Finance">
                                                            <span class="saas-img-cate">
                                                                Finance                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="20">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Food&amp;Drink,Kitchen,Food,Receipe,Drink,Mocktails,healthyrecipe,restaurants,herb&amp;spices,beverage,Cooking,Diet,Mealplans,nutritions,Culinary,Cuisine,Gourmet,Dining,Restaurants,Foodie,Gastronomy,Food trends,Healthy eating,Nutrition,Meal prep,Baking,Desserts,Beverages,Cocktails,Wine,Beer,Spirits,Coffee,Tea,Smoothies,Juices,Organic food,Farm-to-table,Ethnic food,Street food,Snacks,Appetizers,Main courses,Breakfast,Brunch,Lunch,Dinner,Vegetarian,Vegan,Gluten-free,Dairy-free,Plant-based,Kitchen gadgets,Cooking,Hungry." id="category_Food &amp; Drink" value="Food &amp; Drink">
                                                        <label for="category_Food &amp; Drink">
                                                            <span class="saas-img-cate">
                                                                Food &amp; Drink                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="21">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Gaming,Games,VideoGames,Online games,Board games,Mobile games,Console games,PC games,Arcade games,Puzzle games,Strategy games,Action games,Adventure games,Role-playing games,Simulation games,Sports games,Multiplayer games,Educational games,Card games,Game reviews,Game tips,Game guides." id="category_Games" value="Games">
                                                        <label for="category_Games">
                                                            <span class="saas-img-cate">
                                                                Games                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="22">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="General,Horoscope,Inspiration,GeneralBlog" id="category_General" value="General">
                                                        <label for="category_General">
                                                            <span class="saas-img-cate">
                                                                General                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="23">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Gift,giftcards,presents,giftvoucher,giveaways" id="category_Gift" value="Gift">
                                                        <label for="category_Gift">
                                                            <span class="saas-img-cate">
                                                                Gift                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="24">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Health&amp;Fitness,wellness,mentalhealth,physicalwellness,medical,Health,fitness,Cancer,Medline&amp;Drugs,exrercise,Gym,Workout,heal,Meditation,Yoga,Weightloss,Depression,Deseasesandcondtions,Herbalremedies,Ayurved,exercise,treatment,HealthCare,Medicine,Dental,Healthy,healthandfood,healthfitness,healthsites,healthcheckup,diseases" id="category_Health &amp; Fitness" value="Health &amp; Fitness">
                                                        <label for="category_Health &amp; Fitness">
                                                            <span class="saas-img-cate">
                                                                Health &amp; Fitness                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="25">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Home&amp;Garden,HomeDecor,architecture,home,Interiordesign,furniture,hacks,garden,house,vastu,treehouse,architect,homeimprovement,décor,tinyhouses,Home,homes,HomeandGarden,homecare" id="category_Home &amp; Garden" value="Home &amp; Garden">
                                                        <label for="category_Home &amp; Garden">
                                                            <span class="saas-img-cate">
                                                                Home &amp; Garden                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="26">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Humor,Comedy,Jokes,Laughter,Satire,Stand-up,Parody,Pranks,Sarcasm,Irony,Puns,Sketch Comedy,Improvisation,Humorous,Funny,Gags,Comic Relief,Witicism" id="category_Humor" value="Humor">
                                                        <label for="category_Humor">
                                                            <span class="saas-img-cate">
                                                                Humor                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="27">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Internet&amp;Telecom,internet,web,www,websecurity,Networks,Cable,Hardware,CyberSecurity,Hacking,Telecom,networking" id="category_Internet &amp; Telecom" value="Internet &amp; Telecom">
                                                        <label for="category_Internet &amp; Telecom">
                                                            <span class="saas-img-cate">
                                                                Internet &amp; Telecom                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="28">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Law&amp;Government,Government,Law,Legal,govt,Legislation,Public Policy,Judiciary,Courts,Regulations,Compliance,Legal Advice,Constitutional Law,Administrative Law,Civil Rights,Criminal Justice,Law Enforcement,Legal Studies,Government Policies,Public Administration,Municipal Government,State Government,Federal Government,International Law,Human Rights,Legal Services,Legal Aid,Public Safety,Civic Engagement,Election Law,Lobbying,Accident" id="category_Law &amp; Government" value="Law &amp; Government">
                                                        <label for="category_Law &amp; Government">
                                                            <span class="saas-img-cate">
                                                                Law &amp; Government                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="29">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Leisure&amp;Hobbies,contest,Tattoos,leasure,hobbies" id="category_Leisure &amp; Hobbies" value="Leisure &amp; Hobbies">
                                                        <label for="category_Leisure &amp; Hobbies">
                                                            <span class="saas-img-cate">
                                                                Leisure &amp; Hobbies                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="30">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Books,Magazine,reading,Articles,comics,e-book,Journal,Periodicals,Editorials,Essays,Columns,Literature,Publications,Subscriptions,Digest,Serials,Newsletters,Pamphlets,Bulletins,Yearbooks,Cover Stories,Lifestyle Magazines,Trade Magazines,Celebrity Magazines" id="category_Magazine" value="Magazine">
                                                        <label for="category_Magazine">
                                                            <span class="saas-img-cate">
                                                                Magazine                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="31">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Gas&amp;Oil,Machines,Machine,Tools,Tool,Equipments,Machinery,Welding,Blending,Cutting,Production,Forging,Molding,CNC,Robotics,Quality Control,Drilling,Milling,Turning,Extrusion,Machining,Metalworking,Electroplating,Material Handling,Automation,Fabrication,Packaging,Industrial Design,Prototyping,Manufacturing" id="category_Manufacturing" value="Manufacturing">
                                                        <label for="category_Manufacturing">
                                                            <span class="saas-img-cate">
                                                                Manufacturing                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="32">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Marketing&amp;Advertising,Marketing,Commercial,Advertising,buy&amp;sell,SEO,SocialMedia,LeadGeneration,Homediy,Advertise,Analytics,DigitalMarketing,Branding,Campaigns,Promotions,Public Relations,Content Marketing,Email Marketing,Influencer Marketing,Affiliate Marketing,Print Advertising,Video Advertising,Event,Marketing Strategy,Market Research,Consumer Behavior,Customer Engagement,Marketing Automation,Retargeting,Sponsorships,Trade Shows,Branded Content" id="category_Marketing &amp; Advertising" value="Marketing &amp; Advertising">
                                                        <label for="category_Marketing &amp; Advertising">
                                                            <span class="saas-img-cate">
                                                                Marketing &amp; Advertising                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="33">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Music,Songs,SongsAlbums,Singer,videos,Albums,Artists,Bands,Concerts,Live Music,Instrumentals,Genres,Lyrics,Melodies,Performances,Soundtracks,Recordings,Releases,Compositions,Playlist,Music Industry,Music Production,DJ,Remixes" id="category_Music" value="Music">
                                                        <label for="category_Music">
                                                            <span class="saas-img-cate">
                                                                Music                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="34">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="News&amp;Media,News,GeneralNews,Celebrity,Media,Headline,PressRelease,Journalism,Broadcast,Reporting,Newspaper,Breaking News,Current Affairs,Editorials,Feature Stories,News Analysis,Press Coverage,Public Relations,Broadcast Media,Print Media,Online Media,News Outlets,Journalistic Ethics,Fact-Checking,Media Coverage,News Sources,Photojournalism,Documentary" id="category_News &amp; Media" value="News &amp; Media">
                                                        <label for="category_News &amp; Media">
                                                            <span class="saas-img-cate">
                                                                News &amp; Media                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="35">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Photography,Photoshoot,Photoshop,Photos,Camera,Lens,Shutter,Aperture,Exposure,Composition,Lighting,Portrait,Landscape,Street Photography,Black and White,Color,Fine Art,Digital Photography,Analog Photography,Studio,Photojournalism" id="category_Photography" value="Photography">
                                                        <label for="category_Photography">
                                                            <span class="saas-img-cate">
                                                                Photography                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="36">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Politics,Politicians,Elections,Political Parties,Democracy,Policy,Politicians,Voting,Political Systems,Public Policy,Political Commentary,Lobbying,Political Leadership,International Relations,Diplomacy,Political Process" id="category_Politics" value="Politics">
                                                        <label for="category_Politics">
                                                            <span class="saas-img-cate">
                                                                Politics                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="37">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Quotes" id="category_Quotes" value="Quotes">
                                                        <label for="category_Quotes">
                                                            <span class="saas-img-cate">
                                                                Quotes                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="38">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="realestate,Loan,Mortgage,Property,Housing,buyhome,construction,Realtor,Agent,Listings,Rental,Lease,Landlord,Tenant,Commercial,Residential,Apartment,Condo,Townhouse,Homebuying,Selling,Rental Property,Property Management,Real Estate Transactions,Home Inspection,Closing,Zoning,Land Use,Property Tax" id="category_Real estate" value="Real estate">
                                                        <label for="category_Real estate">
                                                            <span class="saas-img-cate">
                                                                Real estate                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="39">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="spanish,U.S,Russian,china,europe,culture,society,French,Phillipines,Australia,Italian,Region" id="category_Region" value="Region">
                                                        <label for="category_Region">
                                                            <span class="saas-img-cate">
                                                                Region                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="40">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="review,ratings,Reviews,Testimonials,Feedback,Critiques,Comments,Opinions,Impressions,Reactions" id="category_Reviews" value="Reviews">
                                                        <label for="category_Reviews">
                                                            <span class="saas-img-cate">
                                                                Reviews                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="41">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Science,Sci-fi,Sci,World,innovation,Interesting,howto,FYI,powerandlight,Earth,Universe,space,Energy,Research,Discovery,Experiment,Theory,Data,Laboratory,Scientific Method,Evolution,Revolution,Genetics,Biology,Chemistry,Physics,Astronomy,Geology,Paleontology,Archaeology,Neuroscience,Biotechnology,Meteorology,Space Travel,Artificial Intelligence,Robotics,Black Holes,Cloning" id="category_Science" value="Science">
                                                        <label for="category_Science">
                                                            <span class="saas-img-cate">
                                                                Science                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="42">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Shopping,Grossary,BuyingGuide,ProductReviews,Ecommerce,BeautyShopping,Retail,Online Shopping,Discounts,Deals,Sales,Coupons,Bargains,Promotions,Offers,Clearance,Shopping Trends,Return Policy,Payment,Shopping Apps,Wishlists,End-of-Season Sales,Flash Sales,Limited-Time Offers,New Arrivals,Pre-Orders,Exclusives" id="category_Shopping" value="Shopping">
                                                        <label for="category_Shopping">
                                                            <span class="saas-img-cate">
                                                                Shopping                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="43">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Spanish" id="category_Spanish" value="Spanish">
                                                        <label for="category_Spanish">
                                                            <span class="saas-img-cate">
                                                                Spanish                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="44">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Spirituality,Astrology,Horoscope,Numerology,Meditation,Mindfulness,Yoga,Chakras,Energy Healing,Crystal Healing,Tarot,Divine,Spirit,Angel Numbers,Intuition,Peace,Aura Reading,Astral Projection,Past Life Regression,Dream Interpretation,Sacred Rituals,Holistic Living,Consciousness,Higher Self,Soul Connection,Karma,Dharma,Synchronicity,Gratitude,Compassion,Love and Light,Inner Healing,Sacred Texts,Theology,Faith,Sacred Spaces,Pilgrimage" id="category_Spirituality" value="Spirituality">
                                                        <label for="category_Spirituality">
                                                            <span class="saas-img-cate">
                                                                Spirituality                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="45">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Sports,Soccer,Football,Wrestling,Basketball,Hockey,Baseball,volleyball,Boxing,Tennis,Golf,Cricket,Rugby,Swimming,Cycling,Gymnastics,Martial Arts,Surfing,Skateboarding,Snowboarding,Skiing,Archery,Badminton,Sailing,Rowing,Climbing,Triathlon,Water Polo,Softball,Lacrosse,Field Hockey,Polo,Motorsport,Fencing,Bobsleigh,Skeleton,Curling,Ice Dancing,Taekwondo,Rafting,Powerlifting,Weightlifting,CrossFit,Boccia,Shooting,Biathlon,Ski Jumping,Ice Fishing" id="category_Sports" value="Sports">
                                                        <label for="category_Sports">
                                                            <span class="saas-img-cate">
                                                                Sports                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="46">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Technology,Tech,WebDevelopment,Blockchain,WebHosting,BigData,Nanotechnology,AI,Artificial Intelligence,Cybersecurity,Cloud Computing,5G,Robotics,Automation,Biotechnology,Smart Devices,Data Privacy,Biometrics,3D Printing,Satellite,Drones,Urban Mobility,EduTech,Edutainment" id="category_Technology" value="Technology">
                                                        <label for="category_Technology">
                                                            <span class="saas-img-cate">
                                                                Technology                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="47">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Travelling,Outing,Travel,Hotel,Outdoor,Tour,TravelInsuanace,Flights,HotelReviews,Wildlife,Holidays,Destination,TravelTips,Adventure,hiking&amp;camping,hunting&amp;fishing,Tourism,Vacation,Getaway,Journey,Exploration,Backpacking,Road Trip,Cruise,Expedition,Mountain,Staycation,Landmarks,Ecotourism,Solo Travel,Adventure Travel,Luxury Travel,Budget Travel,Remote Destinations,Off-the-Beaten-Path,Hidden Gems,Travel Communities,Travel Agencies,Documentation,Passports,Visas,Vaccinations,Jet Lag" id="category_Travelling" value="Travelling">
                                                        <label for="category_Travelling">
                                                            <span class="saas-img-cate">
                                                                Travelling                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="48">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="Webdevelopment,WebDesign,Web,Theme,MultiMedia,Software,App,Cloud,webdevelopment,Infographic,CSS,wordpress,Photoshop,Webhosting,WPthemes,Fonts,Logos,Plugin,Developer,development,programming,Cloudcomputing,Frontend,Backend,HTML5,JavaScript,Mobile Development,E-commerce,Responsive Design,Animation,Graphic Design,DevOps,Coding" id="category_Web development" value="Web development">
                                                        <label for="category_Web development">
                                                            <span class="saas-img-cate">
                                                                Web development                                                             </span>
                                                        </label>
                                                    </div><div class="form-group" data-no="49">
                                                        <input type="checkbox" class="checkboxll search_categories" name="categories" data-tags="wedding,weddingclothes,Destinationwedding,Grooming,marriage,Bride,Groom,Wedding Party,Wedding Guests,Groomsmen,Bridesmaids,Flower Girl,Ring Bearer,Officiant,programming" id="category_Wedding" value="Wedding">
                                                        <label for="category_Wedding">
                                                            <span class="saas-img-cate">
                                                                Wedding                                                             </span>
                                                        </label>
                                                    </div></div>
                                            </form>
                                                                                                                                    <div class="load-more category">
                                                <a href="#category" class="search_categories_loadmore loadMoreBtn" style="pointer-events:auto;" data-toggle="collapse">Show more</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="card forbidden-card">
                                    <div class="card-header">
                                        <a class="card-link">
                                            Forbidden Category
                                        </a>
                                    </div>
                                    <div id="collapsenine" data-parent="#accordion">
                                        <div class="card-body">

                                            <div class="load-more" style="pointer-events: none;">
                                                <a href="#forbidden-category" class="forbiden_categories_loadmore"
                                                    style="pointer-events:auto;" data-toggle="collapse">Load more
                                                    </a>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="card">
                                    <div class="card-header">
                                        <a class="card-link target-country">
                                            Traffic By Country
                                        </a>
                                    </div>
                                    <div id="collapseten" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                            <div class="load-more country top" style="display:none">
                                                <a href="#countryList" class="countryList_loadmore loadMoreBtn" style="pointer-events:auto;" data-toggle="collapse">Show more</a>
                                            </div>
                                                <div class="form-group">
                                                    <input type="search" class="category-search" placeholder="Search Country" id="countryList_input" autocomplete="off">
                                                </div>
                                                    <div id="first_two">
                                                    <div class="form-group" data-no="1">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="United States" value="United States">
                                                        <label for="United States"><span>United States</span></label>
                                                    </div>

                                                    <div class="form-group" data-no="2">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="India" value="India">
                                                        <label for="India"><span>India</span></label>
                                                    </div>
                                                    </div>
                                                    <div id="countryList" class="collapse load_more_data">
                                                
                                                                                                        <div class="form-group" data-no="3">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="United Kingdom" value="United Kingdom">
                                                        <label for="United Kingdom"><span>United Kingdom</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="4">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="American Samoa" value="American Samoa">
                                                        <label for="American Samoa"><span>American Samoa</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="5">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Australia" value="Australia">
                                                        <label for="Australia"><span>Australia</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="6">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Canada" value="Canada">
                                                        <label for="Canada"><span>Canada</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="7">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Afghanistan" value="Afghanistan">
                                                        <label for="Afghanistan"><span>Afghanistan</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="8">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Åland Islands" value="Åland Islands">
                                                        <label for="Åland Islands"><span>Åland Islands</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="9">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Albania" value="Albania">
                                                        <label for="Albania"><span>Albania</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="10">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Algeria" value="Algeria">
                                                        <label for="Algeria"><span>Algeria</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="11">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Andorra" value="Andorra">
                                                        <label for="Andorra"><span>Andorra</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="12">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Anguilla" value="Anguilla">
                                                        <label for="Anguilla"><span>Anguilla</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="13">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Antarctica" value="Antarctica">
                                                        <label for="Antarctica"><span>Antarctica</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="14">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Antigua and Barbuda" value="Antigua and Barbuda">
                                                        <label for="Antigua and Barbuda"><span>Antigua and Barbuda</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="15">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Argentina" value="Argentina">
                                                        <label for="Argentina"><span>Argentina</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="16">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Armenia" value="Armenia">
                                                        <label for="Armenia"><span>Armenia</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="17">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Aruba" value="Aruba">
                                                        <label for="Aruba"><span>Aruba</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="18">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Austria" value="Austria">
                                                        <label for="Austria"><span>Austria</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="19">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Azerbaijan" value="Azerbaijan">
                                                        <label for="Azerbaijan"><span>Azerbaijan</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="20">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Bahamas" value="Bahamas">
                                                        <label for="Bahamas"><span>Bahamas</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="21">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Bahrain" value="Bahrain">
                                                        <label for="Bahrain"><span>Bahrain</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="22">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Bangladesh" value="Bangladesh">
                                                        <label for="Bangladesh"><span>Bangladesh</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="23">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Barbados" value="Barbados">
                                                        <label for="Barbados"><span>Barbados</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="24">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Belarus" value="Belarus">
                                                        <label for="Belarus"><span>Belarus</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="25">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Belgium" value="Belgium">
                                                        <label for="Belgium"><span>Belgium</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="26">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Belize" value="Belize">
                                                        <label for="Belize"><span>Belize</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="27">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Bermuda" value="Bermuda">
                                                        <label for="Bermuda"><span>Bermuda</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="28">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Bonaire, Sint Eustatius and Saba" value="Bonaire, Sint Eustatius and Saba">
                                                        <label for="Bonaire, Sint Eustatius and Saba"><span>Bonaire, Sint Eustatius and Saba</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="29">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Botswana" value="Botswana">
                                                        <label for="Botswana"><span>Botswana</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="30">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Bouvet Island" value="Bouvet Island">
                                                        <label for="Bouvet Island"><span>Bouvet Island</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="31">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Brazil" value="Brazil">
                                                        <label for="Brazil"><span>Brazil</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="32">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="British Indian Ocean Territory" value="British Indian Ocean Territory">
                                                        <label for="British Indian Ocean Territory"><span>British Indian Ocean Territory</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="33">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Brunei Darussalam" value="Brunei Darussalam">
                                                        <label for="Brunei Darussalam"><span>Brunei Darussalam</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="34">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Bulgaria" value="Bulgaria">
                                                        <label for="Bulgaria"><span>Bulgaria</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="35">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Burkina Faso" value="Burkina Faso">
                                                        <label for="Burkina Faso"><span>Burkina Faso</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="36">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Burundi" value="Burundi">
                                                        <label for="Burundi"><span>Burundi</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="37">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Cambodia" value="Cambodia">
                                                        <label for="Cambodia"><span>Cambodia</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="38">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Cape Verde" value="Cape Verde">
                                                        <label for="Cape Verde"><span>Cape Verde</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="39">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Cayman Islands" value="Cayman Islands">
                                                        <label for="Cayman Islands"><span>Cayman Islands</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="40">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Chad" value="Chad">
                                                        <label for="Chad"><span>Chad</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="41">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Chile" value="Chile">
                                                        <label for="Chile"><span>Chile</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="42">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Christmas Island" value="Christmas Island">
                                                        <label for="Christmas Island"><span>Christmas Island</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="43">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Colombia" value="Colombia">
                                                        <label for="Colombia"><span>Colombia</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="44">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Comoros" value="Comoros">
                                                        <label for="Comoros"><span>Comoros</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="45">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Cook Islands" value="Cook Islands">
                                                        <label for="Cook Islands"><span>Cook Islands</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="46">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Costa Rica" value="Costa Rica">
                                                        <label for="Costa Rica"><span>Costa Rica</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="47">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Curaçao" value="Curaçao">
                                                        <label for="Curaçao"><span>Curaçao</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="48">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Denmark" value="Denmark">
                                                        <label for="Denmark"><span>Denmark</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="49">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Djibouti" value="Djibouti">
                                                        <label for="Djibouti"><span>Djibouti</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="50">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Dominica" value="Dominica">
                                                        <label for="Dominica"><span>Dominica</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="51">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Ecuador" value="Ecuador">
                                                        <label for="Ecuador"><span>Ecuador</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="52">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Estonia" value="Estonia">
                                                        <label for="Estonia"><span>Estonia</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="53">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Falkland Islands (Malvinas)" value="Falkland Islands (Malvinas)">
                                                        <label for="Falkland Islands (Malvinas)"><span>Falkland Islands (Malvinas)</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="54">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Faroe Islands" value="Faroe Islands">
                                                        <label for="Faroe Islands"><span>Faroe Islands</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="55">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="France" value="France">
                                                        <label for="France"><span>France</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="56">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="French Guiana" value="French Guiana">
                                                        <label for="French Guiana"><span>French Guiana</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="57">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="French Polynesia" value="French Polynesia">
                                                        <label for="French Polynesia"><span>French Polynesia</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="58">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="French Southern Territories" value="French Southern Territories">
                                                        <label for="French Southern Territories"><span>French Southern Territories</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="59">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Gambia" value="Gambia">
                                                        <label for="Gambia"><span>Gambia</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="60">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Georgia" value="Georgia">
                                                        <label for="Georgia"><span>Georgia</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="61">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Germany" value="Germany">
                                                        <label for="Germany"><span>Germany</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="62">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Ghana" value="Ghana">
                                                        <label for="Ghana"><span>Ghana</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="63">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Greenland" value="Greenland">
                                                        <label for="Greenland"><span>Greenland</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="64">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Grenada" value="Grenada">
                                                        <label for="Grenada"><span>Grenada</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="65">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Guadeloupe" value="Guadeloupe">
                                                        <label for="Guadeloupe"><span>Guadeloupe</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="66">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Guinea" value="Guinea">
                                                        <label for="Guinea"><span>Guinea</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="67">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Guinea-Bissau" value="Guinea-Bissau">
                                                        <label for="Guinea-Bissau"><span>Guinea-Bissau</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="68">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Guyana" value="Guyana">
                                                        <label for="Guyana"><span>Guyana</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="69">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Honduras" value="Honduras">
                                                        <label for="Honduras"><span>Honduras</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="70">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Hong Kong" value="Hong Kong">
                                                        <label for="Hong Kong"><span>Hong Kong</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="71">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Hungary" value="Hungary">
                                                        <label for="Hungary"><span>Hungary</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="72">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Iceland" value="Iceland">
                                                        <label for="Iceland"><span>Iceland</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="73">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Iraq" value="Iraq">
                                                        <label for="Iraq"><span>Iraq</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="74">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Jersey" value="Jersey">
                                                        <label for="Jersey"><span>Jersey</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="75">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Kiribati" value="Kiribati">
                                                        <label for="Kiribati"><span>Kiribati</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="76">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Korea, Democratic People's Republic of" value="Korea, Democratic People's Republic of">
                                                        <label for="Korea, Democratic People's Republic of"><span>Korea, Democratic People's Republic of</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="77">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Korea, Republic of" value="Korea, Republic of">
                                                        <label for="Korea, Republic of"><span>Korea, Republic of</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="78">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Kuwait" value="Kuwait">
                                                        <label for="Kuwait"><span>Kuwait</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="79">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Latvia" value="Latvia">
                                                        <label for="Latvia"><span>Latvia</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="80">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Libya" value="Libya">
                                                        <label for="Libya"><span>Libya</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="81">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Lithuania" value="Lithuania">
                                                        <label for="Lithuania"><span>Lithuania</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="82">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Macao" value="Macao">
                                                        <label for="Macao"><span>Macao</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="83">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Mali" value="Mali">
                                                        <label for="Mali"><span>Mali</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="84">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Marshall Islands" value="Marshall Islands">
                                                        <label for="Marshall Islands"><span>Marshall Islands</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="85">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Mauritius" value="Mauritius">
                                                        <label for="Mauritius"><span>Mauritius</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="86">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Mexico" value="Mexico">
                                                        <label for="Mexico"><span>Mexico</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="87">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Monaco" value="Monaco">
                                                        <label for="Monaco"><span>Monaco</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="88">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Nauru" value="Nauru">
                                                        <label for="Nauru"><span>Nauru</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="89">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Niger" value="Niger">
                                                        <label for="Niger"><span>Niger</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="90">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Northern Mariana Islands" value="Northern Mariana Islands">
                                                        <label for="Northern Mariana Islands"><span>Northern Mariana Islands</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="91">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Norway" value="Norway">
                                                        <label for="Norway"><span>Norway</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="92">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Pitcairn" value="Pitcairn">
                                                        <label for="Pitcairn"><span>Pitcairn</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="93">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Senegal" value="Senegal">
                                                        <label for="Senegal"><span>Senegal</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="94">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Serbia" value="Serbia">
                                                        <label for="Serbia"><span>Serbia</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="95">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Seychelles" value="Seychelles">
                                                        <label for="Seychelles"><span>Seychelles</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="96">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Slovakia" value="Slovakia">
                                                        <label for="Slovakia"><span>Slovakia</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="97">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="South Africa" value="South Africa">
                                                        <label for="South Africa"><span>South Africa</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="98">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="South Sudan" value="South Sudan">
                                                        <label for="South Sudan"><span>South Sudan</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="99">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Suriname" value="Suriname">
                                                        <label for="Suriname"><span>Suriname</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="100">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Swaziland" value="Swaziland">
                                                        <label for="Swaziland"><span>Swaziland</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="101">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Taiwan, Province of China" value="Taiwan, Province of China">
                                                        <label for="Taiwan, Province of China"><span>Taiwan, Province of China</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="102">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Tanzania, United Republic of" value="Tanzania, United Republic of">
                                                        <label for="Tanzania, United Republic of"><span>Tanzania, United Republic of</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="103">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Thailand" value="Thailand">
                                                        <label for="Thailand"><span>Thailand</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="104">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Tokelau" value="Tokelau">
                                                        <label for="Tokelau"><span>Tokelau</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="105">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Turkey" value="Turkey">
                                                        <label for="Turkey"><span>Turkey</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="106">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Viet Nam" value="Viet Nam">
                                                        <label for="Viet Nam"><span>Viet Nam</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="107">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Wallis and Futuna" value="Wallis and Futuna">
                                                        <label for="Wallis and Futuna"><span>Wallis and Futuna</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="108">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Western Sahara" value="Western Sahara">
                                                        <label for="Western Sahara"><span>Western Sahara</span></label>
                                                    </div>
                                                                                                        <div class="form-group" data-no="109">
                                                        <input type="checkbox" class="checkboxll countryList" name="countryList" id="Zimbabwe" value="Zimbabwe">
                                                        <label for="Zimbabwe"><span>Zimbabwe</span></label>
                                                    </div>
                                                    
                                                </div>
                                            </form>
                                            <div class="load-more country">
                                                <a href="#countryList" class="countryList_loadmore loadMoreBtn" style="pointer-events:auto;" data-toggle="collapse">Show more</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <a class="card-link language">
                                            Language <span class="language-new"><img src="https://lp-latest.elsnerdev.com/template_elsner/images/New.svg"></span>
                                        </a>
                                    </div>
                                    <div id="collapseten" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                                                        <div class="load-more language top" style="display:none">
                                                <a href="#languageList" class="languageList_loadmore loadMoreBtn" style="pointer-events:auto;" data-toggle="collapse">Show more
                                                </a>
                                            </div>
                                                                                            <div class="form-group">
                                                    <input type="search" class="category-search" placeholder="Search Language" id="languageList_input">
                                                </div>
                                                    <div id="first_two_language">
                                                                                                                <div class="form-group" data-no="1">
                                                            <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="Afrikaans" value="Afrikaans">
                                                            <label for="Afrikaans"><span>Afrikaans</span></label>
                                                        </div>

                                                        <div class="form-group" data-no="2">
                                                            <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="Albanian" value="Albanian">
                                                            <label for="Albanian"><span>Albanian</span></label>
                                                        </div>
                                                                                                            </div>
                                                    <div id="languageList" class="collapse load_more_data">
                                                
                                                                                                                                                                                <div class="form-group" data-no="3">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Arabic" value="Arabic">
                                                                <label for="language_Arabic">
                                                                    <span>Arabic</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="4">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Armenian" value="Armenian">
                                                                <label for="language_Armenian">
                                                                    <span>Armenian</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="5">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Azerbaijani" value="Azerbaijani">
                                                                <label for="language_Azerbaijani">
                                                                    <span>Azerbaijani</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="6">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Bulgarian" value="Bulgarian">
                                                                <label for="language_Bulgarian">
                                                                    <span>Bulgarian</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="7">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Catalan" value="Catalan">
                                                                <label for="language_Catalan">
                                                                    <span>Catalan</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="8">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Croatian" value="Croatian">
                                                                <label for="language_Croatian">
                                                                    <span>Croatian</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="9">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Czech" value="Czech">
                                                                <label for="language_Czech">
                                                                    <span>Czech</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="10">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Dutch" value="Dutch">
                                                                <label for="language_Dutch">
                                                                    <span>Dutch</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="11">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_English" value="English">
                                                                <label for="language_English">
                                                                    <span>English</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="12">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Estonian" value="Estonian">
                                                                <label for="language_Estonian">
                                                                    <span>Estonian</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="13">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_French" value="French">
                                                                <label for="language_French">
                                                                    <span>French</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="14">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Greek" value="Greek">
                                                                <label for="language_Greek">
                                                                    <span>Greek</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="15">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Hebrew" value="Hebrew">
                                                                <label for="language_Hebrew">
                                                                    <span>Hebrew</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="16">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Hindi" value="Hindi">
                                                                <label for="language_Hindi">
                                                                    <span>Hindi</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="17">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Hungarian" value="Hungarian">
                                                                <label for="language_Hungarian">
                                                                    <span>Hungarian</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="18">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Icelandic" value="Icelandic">
                                                                <label for="language_Icelandic">
                                                                    <span>Icelandic</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="19">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Indonesian" value="Indonesian">
                                                                <label for="language_Indonesian">
                                                                    <span>Indonesian</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="20">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Italian" value="Italian">
                                                                <label for="language_Italian">
                                                                    <span>Italian</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="21">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Japanese" value="Japanese">
                                                                <label for="language_Japanese">
                                                                    <span>Japanese</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="22">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Kannada" value="Kannada">
                                                                <label for="language_Kannada">
                                                                    <span>Kannada</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="23">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Kazakh" value="Kazakh">
                                                                <label for="language_Kazakh">
                                                                    <span>Kazakh</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="24">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Latvian" value="Latvian">
                                                                <label for="language_Latvian">
                                                                    <span>Latvian</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="25">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Lithuanian" value="Lithuanian">
                                                                <label for="language_Lithuanian">
                                                                    <span>Lithuanian</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="26">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Macedonian" value="Macedonian">
                                                                <label for="language_Macedonian">
                                                                    <span>Macedonian</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="27">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Norwegian" value="Norwegian">
                                                                <label for="language_Norwegian">
                                                                    <span>Norwegian</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="28">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Polish" value="Polish">
                                                                <label for="language_Polish">
                                                                    <span>Polish</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="29">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Portuguese" value="Portuguese">
                                                                <label for="language_Portuguese">
                                                                    <span>Portuguese</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="30">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Punjabi" value="Punjabi">
                                                                <label for="language_Punjabi">
                                                                    <span>Punjabi</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="31">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Russian" value="Russian">
                                                                <label for="language_Russian">
                                                                    <span>Russian</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="32">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Sinhala" value="Sinhala">
                                                                <label for="language_Sinhala">
                                                                    <span>Sinhala</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="33">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Slovak" value="Slovak">
                                                                <label for="language_Slovak">
                                                                    <span>Slovak</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="34">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Spanish" value="Spanish">
                                                                <label for="language_Spanish">
                                                                    <span>Spanish</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="35">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Swahili" value="Swahili">
                                                                <label for="language_Swahili">
                                                                    <span>Swahili</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="36">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Thai" value="Thai">
                                                                <label for="language_Thai">
                                                                    <span>Thai</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="37">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Urdu" value="Urdu">
                                                                <label for="language_Urdu">
                                                                    <span>Urdu</span>
                                                                </label>
                                                            </div>
                                                                                                                        <div class="form-group" data-no="38">
                                                                <input type="checkbox" class="checkboxll languageList" name="languageList[]" id="language_Zulu" value="Zulu">
                                                                <label for="language_Zulu">
                                                                    <span>Zulu</span>
                                                                </label>
                                                            </div>
                                                                                                                    
                                                    </div>
                                            </form>
                                                                                        <div class="load-more language">
                                                <a href="#languageList" class="languageList_loadmore loadMoreBtn" style="pointer-events:auto;" data-toggle="collapse">Show more
                                                </a>
                                            </div>
                                                                                    </div>
                                    </div>
                                </div>
                                                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <a class="card-link collapsed cart-link-icon" data-toggle="collapse" href="#collapsefive">
                                            ahrefs traffic
                                        </a>
                                    </div>
                                    <div id="collapsefive" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                <div class="min-form">
                                                    <!-- <h6>Custom Range</h6> -->
                                                    <input type="text" name="samrush_traffic numeric" id="min_ahref_traffic" class="form-control numeric" placeholder="min" autocomplete="off" maxlength="8">
                                                    <input type="text" name="samrush_traffic numeric" id="max_ahref_traffic" class="form-control numeric" placeholder="max" autocomplete="off" maxlength="8">
                                                    <button class="btn button btn-primary ahrefMinMax" type="button" disabled="">GO</button>
                                                    <p id="ahref_traffic_msg" style="display: none; color:red;"><b>Enter
                                                            valid value</b></p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <a class="card-link collapsed cart-link-icon" data-toggle="collapse" href="#collapsesix">
                                            semrush traffic
                                        </a>
                                    </div>
                                    <div id="collapsesix" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                <div class="min-form">
                                                    <!-- <h6>Custom Range</h6> -->
                                                    <input type="text" name="samrush_traffic numeric" id="min_samrush_traffic" class="form-control numeric" placeholder="min" autocomplete="off" maxlength="8">
                                                    <input type="text" name="samrush_traffic numeric" id="max_samrush_traffic" class="form-control numeric" placeholder="max" autocomplete="off" maxlength="8">
                                                    <button class="btn button btn-primary samrushMinMax" type="button" disabled="">GO</button>
                                                    <p id="samrush_traffic_msg" style="display: none; color:red;">
                                                        <b>Enter valid value</b>
                                                    </p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <a class="card-link collapsed cart-link-icon" data-toggle="collapse" href="#collapsetwo">
                                            Domain Rating
                                        </a>
                                    </div>
                                    <div id="collapsetwo" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                <div class="min-form">
                                                    <input type="text" name="dr numeric" id="min_dr" class="form-control numeric" placeholder="min" autocomplete="off" maxlength="8">
                                                    <input type="text" name="dr numeric" id="max_dr" class="form-control numeric" placeholder="max" autocomplete="off" maxlength="8">
                                                    <button class="btn button btn-primary drminmax" type="button" disabled="">GO</button>
                                                    <p id="dr_msg" style="display: none; color:red;"><b>Enter valid value</b></p>
                                                    <p id="dr_msg1" style="display: none; color:red;"><b>DR must be 0 to 100</b></p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <a class="card-link collapsed cart-link-icon" data-toggle="collapse" href="#collapsethree">
                                            Authority Score
                                        </a>
                                    </div>
                                    <div id="collapsethree" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                <div class="min-form">
                                                    <input type="text" name="authority_score numeric" id="min_authority" class="form-control numeric" placeholder="min" autocomplete="off" maxlength="8">
                                                    <input type="text" name="authority_score numeric" id="max_authority" class="form-control numeric" placeholder="max" autocomplete="off" maxlength="8">
                                                    <button class="btn button btn-primary authorityminmax" type="button" disabled="">GO</button>
                                                    <p id="authority_msg" style="display: none; color:red;"><b>Enter valid value</b></p>
                                                    <p id="authority_msg1" style="display: none; color:red;"><b>AS must be 0 to 100</b></p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <a class="card-link collapsed cart-link-icon" data-toggle="collapse" href="#collapseseven">
                                            price
                                        </a>
                                    </div>
                                    <div id="collapseseven" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                <div class="min-form">
                                                    <!-- <h6>Custom Range</h6> -->
                                                    <input type="text" name="priceMin numeric" id="priceMin" class="form-control numeric" placeholder="min" autocomplete="off" maxlength="5">
                                                    <input type="text" name="priceMax numeric" id="priceMax" class="form-control numeric" placeholder="max" autocomplete="off" maxlength="5">
                                                    <button class="btn button btn-primary priceMinMax" type="button" disabled="">GO</button>
                                                    <p id="price_traffic_msg" style="display: none; color:red;">
                                                        <b>Enter valid value</b>
                                                    </p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> 
                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <a class="card-link collapsed cart-link-icon" data-toggle="collapse" href="#collapseseventeen">
                                            Spam Score <span class="language-new"><img src="https://lp-latest.elsnerdev.com/template_elsner/images/New.svg"></span>
                                        </a>
                                    </div>
                                    <div id="collapseseventeen" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                <div class="min-form">
                                                    <input type="text" name="spam_score numeric" id="min_spam_score" class="form-control numeric" placeholder="min" autocomplete="off" maxlength="8">
                                                    <input type="text" name="spam_score numeric" id="max_spam_score" class="form-control numeric" placeholder="max" autocomplete="off" maxlength="8">
                                                    <button class="btn button btn-primary spamScoreMinMax" type="button" disabled="">GO</button>
                                                    <p id="spam_score_msg" style="display: none; color:red;"><b>Enter
                                                            valid value</b></p>
                                                    <p id="spam_score_msg1" style="display: none; color:red;"><b>Spam Score must be 0 to 100</b></p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card">
                                    <div class="card-header">
                                        <a class="card-link">
                                            Link Type
                                        </a>
                                    </div>
                                    <div id="collapsefour" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                <div class="form-group">
                                                    <input type="radio" class="checkboxll link_type" name="link_type" id="link_type_checkbox1" value="Dofollow">
                                                    <label for="link_type_checkbox1"><span>Dofollow</span></label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="radio" class="checkboxll link_type" name="link_type" id="link_type_checkbox2" value="Nofollow">
                                                    <label for="link_type_checkbox2"><span>Nofollow</span></label>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <a class="card-link collapsed cart-link-icon" data-toggle="collapse" href="#collapseeleven">
                                            link insertion price
                                        </a>
                                    </div>
                                    <div id="collapseeleven" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                <div class="min-form">
                                                    <input type="text" name="liPriceMin numeric" id="liPriceMin"
                                                        class="form-control numeric" placeholder="min"
                                                        autocomplete="off" maxlength="5">
                                                    <input type="text" name="liPriceMax numeric" id="liPriceMax"
                                                        class="form-control numeric" placeholder="max"
                                                        autocomplete="off" maxlength="5">
                                                    <button class="btn button btn-primary liPriceMinMax" type="button"
                                                        disabled="">GO</button>
                                                    <p id="li_price_traffic_msg" style="display: none; color:red;">
                                                        <b>Enter valid value</b>
                                                    </p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> -->
                                                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <a class="card-link collapsed cart-link-icon" data-toggle="collapse" href="#collapsetwelve">
                                            TAT
                                        </a>
                                    </div>
                                    <div id="collapsetwelve" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                <div class="tat_min_form">
                                                    <select name="tat_filter" id="tat_filter" data-select2-id="select2-data-tat_filter" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
                                                        <option value="" data-select2-id="select2-data-4-40bj">Select Day</option>
                                                        <option value="1_day">1 Day</option>
                                                        <option value="2_days">2 Days</option>
                                                        <option value="3_days">3 Days</option>
                                                        <option value="4_days">4 Days</option>
                                                        <option value="5_days">5 Days</option>
                                                        <option value="6_days">6 Days</option>
                                                        <option value="7_days">7 Days</option>
                                                        <option value="8_days">8 Days</option>
                                                        <option value="9_days">9 Days</option>
                                                        <option value="10_days">10 Days</option>
                                                        <option value="11_days">11 Days</option>
                                                        <option value="12_days">12 Days</option>
                                                        <option value="13_days">13 Days</option>
                                                        <option value="14_days">14 Days</option>
                                                        <option value="15_days">15 Days</option>
                                                        <option value="30_days">30 Days</option>
                                                        <option value="60_days">60 Days</option>
                                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="select2-data-3-5u0h" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-tat_filter-container" aria-controls="select2-tat_filter-container"><span class="select2-selection__rendered" id="select2-tat_filter-container" role="textbox" aria-readonly="true" title="Select Day">Select Day</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <a class="card-link collapsed cart-link-icon" data-toggle="collapse" href="#collapsefifteen">
                                            Trust Flow
                                        </a>
                                    </div>
                                    <div id="collapsefifteen" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                <div class="min-form">
                                                    <input type="text" name="trust_flow numeric" id="min_trust_flow" class="form-control numeric" placeholder="min" autocomplete="off" maxlength="8">
                                                    <input type="text" name="trust_flow numeric" id="max_trust_flow" class="form-control numeric" placeholder="max" autocomplete="off" maxlength="8">
                                                    <button class="btn button btn-primary trustFlowMinMax" type="button" disabled="">GO</button>
                                                    <p id="trust_flow_msg" style="display: none; color:red;"><b>Enter
                                                            valid value</b></p>
                                                    <p id="trust_flow_msg1" style="display: none; color:red;"><b>TF must be 0 to 100</b></p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <a class="card-link collapsed cart-link-icon" data-toggle="collapse" href="#collapsesixteen">
                                            Citation Flow
                                        </a>
                                    </div>
                                    <div id="collapsesixteen" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <form>
                                                <div class="min-form">
                                                    <input type="text" name="citation_flow numeric" id="min_citation_flow" class="form-control numeric" placeholder="min" autocomplete="off" maxlength="8">
                                                    <input type="text" name="citation_flow numeric" id="max_citation_flow" class="form-control numeric" placeholder="max" autocomplete="off" maxlength="8">
                                                    <button class="btn button btn-primary citationFlowMinMax" type="button" disabled="">GO</button>
                                                    <p id="citation_flow_msg" style="display: none; color:red;"><b>Enter
                                                            valid value</b></p>
                                                    <p id="citation_flow_msg1" style="display: none; color:red;"><b>CF must be 0 to 100</b></p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                                
                                
                                
                            </div>
                        </div>
                    </aside>
                </div>
                <div class="col-md-12 col-lg-10 marketplace-sidebar">
                    <div class="marketplace-table">
                        <table id="marketplaceTable" class="table" width="100%" border="0">
                            <thead>
                                <tr class="table-header">
                                    <th>Website URL</th>
                                    <th>DA</th>
                                    <th>Org. Traffic</th>
                                    <th>Total Visits</th>
                                    <th>TAT</th>
                                    <th>Backlinks</th>
                                    <th>Guest Post</th>
                                    <th>Link Insertion</th>
                                    <th class="cart_wishlist_cta"></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            let token = localStorage.getItem("api_token");
            if (!token) window.location.href = "{{ route('logout') }}";

            setTimeout(function () {
                let newUrl = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            }, 2000);
            
            $(document).on("contextmenu", function (e) {
                e.preventDefault();
            });

            $('#marketplace_search').css({
                'user-select': 'auto'
            });

            $(document).on("keydown", function (e) {
                let isSearchBox = $(e.target).attr('id') === 'marketplace_search';

                let blockedKeys = ["u", "s", "i", "F12"];
                let clipboardKeys = ["c", "x", "a", "v"];

                if (e.ctrlKey && blockedKeys.includes(e.key.toLowerCase()) || 
                    e.ctrlKey && e.shiftKey && e.key.toLowerCase() === "i" || 
                    e.metaKey && e.altKey && e.key.toLowerCase() === "j") {
                    e.preventDefault();
                }

                if (e.ctrlKey && clipboardKeys.includes(e.key.toLowerCase()) && !isSearchBox) {
                    e.preventDefault();
                }
            });

            $('input, textarea').on('copy paste cut', function (e) {
                if ($(this).attr('id') !== 'marketplace_search') {
                    e.preventDefault();
                }
            });

            $('body').css({
                'user-select': 'none',
                '-moz-user-select': 'none',
                '-webkit-user-select': 'none',
                '-ms-user-select': 'none'
            });

            $('#marketplaceTable').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('/api/fetch-marketplace-data') }}",
                    type: "GET",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer " + token,
                    },
                    data: function(d) {
                        d.search = $('#marketplace_search').val();
                        d.marketplaceType = 0;
                        d.page_per_size = 25;
                        d.page = 1;
                    },
                    beforeSend: function () {
                        cartStatus = {};
                    },
                    dataSrc: function (res) {
                        if (!res.success) {
                            window.location.href = "{{ route('logout') }}";
                            return [];
                        }

                        cartStatus = res.cartStatus || {};

                        if (res.hasOwnProperty('cartsTotal') && !isNaN(res.cartsTotal) && res.cartsTotal > 0) {
                            $('.notification-number').show();
                            $('#cartcount').text(res.cartsTotal);
                        } else {
                            $('.notification-number').hide();
                        }

                        if (res.hasOwnProperty('walletBalance') && !isNaN(res.walletBalance) && res.walletBalance > 0) {
                            $('#walletBalance').text("$"+res.walletBalance);
                        } else {
                            $('#walletBalance').text('$0');
                        }

                        return res.data || [];
                    },
                    error: function (xhr) {
                        if (xhr.status === 401) {
                            let response = xhr.responseJSON;
                            if (response && response.logout) {
                                localStorage.removeItem("api_token");
                                window.location.href = "/logout";
                            }
                        }
                    }
                },
                pageLength: 25,
                pagingType: "simple",
                info: false,
                lengthChange: false,
                searching: false,
                columns: [
                    {
                        data: "host_url",
                        render: function (data, type, row) {
                            let formattedUrl = data && !data.startsWith('http') ? 'https://' + data : data;
                            let hostUrl = data ? `<a href="${formattedUrl}" target="_blank"><strong>${data}</strong></a>` : '--';

                            let categories = row.category ? row.category.split(',').map(item => item.trim()) : [];
                            let firstCategory = categories.length > 0 ? categories[0] : 'N/A';
                            let extraCategories = categories.slice(1);

                            let categoryHtml = `<span>${firstCategory}</span>`;
                            if (extraCategories.length > 0) {
                                categoryHtml += `
                                    <span class="category-tooltip" data-toggle="tooltip" data-html="true" title="${extraCategories.join(', ')}">
                                        +${extraCategories.length}
                                    </span>
                                `;
                            }

                            return `
                                <div class="website">${hostUrl}</div>
                                <div>Category: ${categoryHtml}</div>
                            `;
                        }
                    },
                    { data: "da", defaultContent: '--' },
                    { data: "ahref", defaultContent: '0' },
                    { data: "semrush", defaultContent: '0' },
                    { data: "tat", defaultContent: '--' },
                    { data: "backlink_count", defaultContent: '--' },
                    { 
                        data: "guest_post_price", 
                        defaultContent: '--',
                        render: function(data, type, row) {
                            return data ? '$' + data : '--';
                        }
                    },
                    { 
                        data: "linkinsertion_price", 
                        defaultContent: '--',
                        render: function(data, type, row) {
                            return data ? '$' + data : '--';
                        }
                    },
                    {
                        data: "wishlist",
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            let isInWishlist = cartStatus[row.website_id] !== undefined && cartStatus[row.website_id] == 1;
                            return `
                                <a href="#" class="btn button btn-primary cart_wishlist_cta wishlist-btn ${isInWishlist ? 'active' : ''}"
                                    data-wishlist="${row.website_id}" data-action="add" id="wishlist_${row.website_id}" 
                                    data-name="${row.host_url}">
                                    <i class="far fa-heart"></i>
                                </a>
                            `;
                        }
                    },
                    {
                        data: "cart",
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            let isInCart = cartStatus[row.website_id] !== undefined && cartStatus[row.website_id] == 0;
                            return `
                                <a rel="nofollow" class="btn button btn-primary cart_btn ${isInCart ? 'active' : ''}"
                                    id="cart_${row.website_id}" data-cart="${row.website_id}" 
                                    data-action="${isInCart ? 'delete' : 'add'}" data-name="${row.host_url}">
                                    <img src="{{ asset('assets/images/buy.png') }}" alt="buy" id="img_${row.website_id}">
                                    <span>${isInCart ? 'Added' : 'Add'}</span>
                                </a>
                            `;
                        }
                    }
                ],
                rowCallback: function(row, data, index) {
                    $(row).addClass('table-detail');
                }
            });

            $(document).on('click', '.cart_btn', function () {
                var website_id = $(this).attr('data-cart');
                var action = $(this).attr('data-action');
                var host_url = $(this).attr('data-name');
                var clientId = $('#end_client_id').val();

                $.ajax({
                    type: "POST",
                    url: "/api/cart/store",
                    contentType: "application/json",
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem("api_token"),
                    },
                    data: JSON.stringify({
                        website_id: website_id,
                        action: action,
                        marketplaceType: 0,
                        competitorsBacklinkAnalysis: true,
                        clientId: clientId,
                    }),
                    dataType: 'json',
                    success: function (response) {
                        var newAction = action === 'add' ? 'delete' : 'add';
                        var newText = newAction === 'add' ? 'Add' : 'Added';

                        $('#cart_' + website_id).children('span').text(newText);
                        $('#cart_' + website_id).toggleClass('active', newAction === 'delete');
                        $('#cart_' + website_id).attr('data-action', newAction);
                        toastr.success(response.message);

                        if (newAction === 'delete') {
                            $('#wishlist_' + website_id).removeClass('active').attr('data-action', 'add')
                                .find('i').removeClass('fas fa-heart').addClass('far fa-heart');
                            $('#blocksites_' + website_id).addClass('disabled');
                        } else {
                            $('#blocksites_' + website_id).removeClass('disabled');
                        }                    
                        
                        $('#cartcount').text(response.cartTotal);
                        if (response.cartTotal == 0) {
                            $('#cartcount').addClass('d-none').text('');
                        } else {
                            $('#cartcount').show().removeClass('d-none').text(response.cartTotal);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 401) {
                            let response = xhr.responseJSON;
                            if (response && response.logout) {
                                localStorage.removeItem("api_token");
                                window.location.href = "/logout";
                            }
                        } else {
                            toastr.error("Something went wrong");
                        }
                    }
                });
            });

            $(document).on('click', '#logout_advertiser', function (e) {
                e.preventDefault();
                localStorage.removeItem("api_token");
                window.location.href = this.href;
            });

            $('#cart_btn_header').on('click', function () {
                var cartCount = $('#cartcount').text().trim();
                var endClientId = $('#end_client_id').val();
                if (!cartCount || parseInt(cartCount) === 0) {
                    toastr.info('Your Cart is Empty');
                } else {
                    var token = localStorage.getItem("api_token");
                    $.ajax({
                        type: "GET",
                        url: "/api/client-cart-data",
                        headers: {
                            "Authorization": "Bearer " + token,
                        },
                        data: { end_client_id: endClientId },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                const walletBalance = encodeURIComponent(response.walletBalance);
                                const cartTotal = encodeURIComponent(response.cartTotal);
                                const userid = encodeURIComponent(response.userid);
                                window.location.href = `{{ route('cart') }}?userid=${userid}&walletBalance=${walletBalance}&cartTotal=${cartTotal}`;
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 401) {
                                window.location.href = "{{ route('logout') }}";
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection