<!-- Top Header Navigation -->
<div class="dd-menu toggle-menu" role="navigation">
    <ul class="sf-menu">
        <li><a href="#" class="void">Home</a>
            <ul class="dropdown">
                <?php
                $item_title = 'Home versions';
                $items[] = ['url'=>'index.html', 'title'=>'Default'];
                $items[] = ['url'=>'index2.html', 'title'=>'Version 2'];
                $items[] = ['url'=>'index3.html', 'title'=>'Version 3'];
                ?>
                @include('theme.partials.header.navigation-item', ['item_title'=>$item_title, 'items' => $items])

                <?php
                $item_title = 'Slider versions';
                $items[] = ['url'=>'index.html', 'title'=>'Default(Flexslider)'];
                $items[] = ['url'=>'index-revslider.html', 'title'=>'Slider Revolution', 'new'=>true];
                $items[] = ['url'=>'hero-carousel.html', 'title'=>'Full Width Carousel'];
                ?>
                @include('theme.partials.header.navigation-item', ['item_title'=>$item_title, 'items' => $items])

                <?php
                $item_title = 'Search Form Positions';
                $items[] = ['url'=>'index.html', 'title'=>'Default(With Main Menu)'];
                $items[] = ['url'=>'search-below-slider.html', 'title'=>'Below Slider'];
                $items[] = ['url'=>'search-over-slider.html', 'title'=>'Over Slider'];
                ?>
                @include('theme.partials.header.navigation-item', ['item_title'=>$item_title, 'items' => $items])

                <?php
                $item_title = 'Header versions';
                $items[] = ['url'=>'index.html', 'title'=>'Default'];
                $items[] = ['url'=>'header-v2.html', 'title'=>'Version 2'];
                $items[] = ['url'=>'header-v3.html', 'title'=>'Version 3'];
                $items[] = ['url'=>'header-v4.html', 'title'=>'Version 4'];
                ?>
                @include('theme.partials.header.navigation-item', ['item_title'=>$item_title, 'items' => $items])
            </ul>
        </li>

        <?php
        $item_title = 'Pages';
        $items[] = ['url'=>'about.html', 'title'=>'About Us'];
        $items[] = ['url'=>'contact.html', 'title'=>'Contact Us'];
        $items[] = ['url'=>'joinus.html', 'title'=>'Signup'];
        $items[] = ['url'=>'404.html', 'title'=>'404 Error Page'];
        $items[] = ['url'=>'add-listing-pricing.html', 'title'=>'Pricing'];
        $items[] = ['url'=>'shortcodes.html', 'title'=>'Shortcodes'];
        $items[] = ['url'=>'typography.html', 'title'=>'Typography'];
        $items[] = ['url'=>'dealers-search.html', 'title'=>'Dealer Search'];
        $items[] = ['url'=>'dealers-search-results.html', 'title'=>'Dealer Search Results'];
        ?>
        @include('theme.partials.header.navigation-item', ['item_title'=>$item_title, 'items' => $items])

        @include('theme.partials.header.megamenu')

        <?php
        $item_title = 'Listing';
        $items[] = ['url'=>'results-list.html', 'title'=>'List View'];
        $items[] = ['url'=>'results-grid.html', 'title'=>'Grid View'];
        $items[] = ['url'=>'vehicle-details.html', 'title'=>'Vehicle Details'];
        $items[] = ['url'=>'add-listing-form.html', 'title'=>'Add new listing'];
        $items[] = ['url'=>'vehicle-comparision.html', 'title'=>'Vehicle Comparision'];
        ?>
        @include('theme.partials.header.navigation-item', ['item_title'=>$item_title, 'items' => $items])

        <?php
        $item_title = 'Users';
        $items[] = ['url'=>'dealer-prosite.html', 'title'=>'Dealer Prosite'];
        $items[] = ['url'=>'user-dashboard.html', 'title'=>'User Dashboard'];
        $items[] = ['url'=>'user-dashboard-saved-searches.html', 'title'=>'Manage Saved Searches'];
        $items[] = ['url'=>'user-dashboard-saved-cars.html', 'title'=>'Manage Saved Cars'];
        $items[] = ['url'=>'user-dashboard-manage-ads.html', 'title'=>'Manage Ads'];
        $items[] = ['url'=>'user-dashboard-profile.html', 'title'=>'User Profile'];
        $items[] = ['url'=>'user-dashboard-settings.html', 'title'=>'User Settings'];
        ?>
        @include('theme.partials.header.navigation-item', ['item_title'=>$item_title, 'items' => $items])

        <?php
        $item_title = 'Gallery';
        $items[] = ['url'=>'gallery-2cols.html', 'title'=>'Gallery 2 Columns'];
        $items[] = ['url'=>'gallery-3cols.html', 'title'=>'Gallery 3 Columns'];
        $items[] = ['url'=>'gallery-4cols.html', 'title'=>'Gallery 4 Columns'];
        $items[] = ['url'=>'gallery-2cols-details.html', 'title'=>'Gallery 2 Columns with Details'];
        $items[] = ['url'=>'gallery-3cols-details.html', 'title'=>'Gallery 3 Columns with Details'];
        $items[] = ['url'=>'gallery-4cols-details.html', 'title'=>'Gallery 4 Columns with Details'];
        ?>
        @include('theme.partials.header.navigation-item', ['item_title'=>$item_title, 'items' => $items])

        <?php
        $item_title = 'Blog';
        $items[] = ['url'=>'blog.html', 'title'=>'Blog List'];
        $items[] = ['url'=>'blog-masonry.html', 'title'=>'Blog Masonry'];
        $items[] = ['url'=>'single-post.html', 'title'=>'Single Post'];
        $items[] = ['url'=>'single-post-review.html', 'title'=>'Single Review Post'];
        ?>
        @include('theme.partials.header.navigation-item', ['item_title'=>$item_title, 'items' => $items])
    </ul>
</div>
<!-- End Top Header Navigation -->