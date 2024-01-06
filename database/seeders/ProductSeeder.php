<?php

namespace Database\Seeders;
use App\Models\Categorie;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {  
       
        $categories = ['Pasta & rice','Vegetables', 'Fruits','Dairy','Bakery', 'Meat', 'Snacks','Sauces & oils'  ,'Canned','Drinks'];
        $names= [
            'Dairy' => ['Cereals','Milk','Butter','Eggs','Cheese','Coffee','Tea'],
            'Bakery' => ['Bread','Muffins','Cookies','Apple Pie','Brownies'],
            'Drinks' => ['Wine' , 'Beer' , 'Whiskey' , 'Apple Juice' , 'Coke', 'Pepsi'],
            'Sauces & oils' => ['Vinegar','Hot sauce','Ketchup','Mustard','Olive oil'],
            'Pasta & rice' => ['Spagetti','Lasagne', 'Rice', 'Noodles'],
            'Snacks' => ['Chocolate','Chips','Oreo','Pop corn'],
            'Fruits'=> ['Apples','Bananas','Strawberries','Grapes'],
            'Vegetables' => ['Tomatos', 'Potatos','Cucumbers','Onions','Carrots'],
            'Canned' => ['Tuna','Beans','Peas','Corn','Olives'],
            'Meat' => ['Beef','Chicken','Fish','Pork','Burger','Lamb','Turkey','Bacon']
        ];
        $pictures= [
            'Dairy' => ['https://www.pngitem.com/pimgs/m/181-1811924_transparent-cereal-box-png-cookie-crisp-cereal-box.png','https://img2.cgtrader.com/items/717115/a69ed5d165/large/milk-carton-3d-model-obj-mtl-fbx-blend.png','https://media.pakfactory.com/catalog/product/cache/c7a30112d165d3b39569235f6bc121aa/1/_/1_dsc7813.jpg','https://www.pngitem.com/pimgs/m/664-6642383_egg-carton-fs-800g-01b-small-soy-egg.png','https://www.navalanka.lk/wp-content/uploads/2018/11/00051491.jpg','https://www.pngitem.com/pimgs/m/284-2843710_coffee-powder-pack-hd-png-download.png','https://www.pngitem.com/pimgs/m/45-458109_lipton-tea-bag-100-png-download-lipton-tea.png'],
            'Bakery' => ['https://w7.pngwing.com/pngs/31/101/png-transparent-bakery-ingredient-brown-bread-cake-whole-wheat.png','https://www.specialtyfood.com/media/multi_image_uploader/source/Apple_Cinnamon_Muffin_VMG.jpg','https://www.famousamos.com/sites/default/files/2022-05/british-salted-caramel-7oz.png','https://www.mariecallendersmeals.com/sites/g/files/qyyrlu306/files/images/products/apple-pie-60583.png','https://www.pngitem.com/pimgs/m/482-4822210_double-fudge-brownie-mix-chocolate-brownie-hd-png.png'],
            'Drinks' => ['https://www.tintohn.com/wp-content/uploads/2020/04/blue-nun-merlot.jpg','https://toppng.com/uploads/preview/corona-beer-corona-extra-710-11562961013wpedmqnrky.png','https://www.pngitem.com/pimgs/m/165-1652752_whisky-whiskey-png-jack-daniels-70-cl-transparent.png','https://toppng.com/uploads/preview/minute-maid-apple-juice-11563194190k9i1yo3kuf.png','https://toppng.com/uploads/preview/coca-cola-bottle-11528338683o0snrfbxt5.png','https://lesgrandeseaux.com/wp-content/uploads/2020/08/Pepsi-max-900x900.jpg'],
            'Sauces & oils' => ['https://www.nicepng.com/png/detail/351-3514118_apple-cider-vinegar.png','https://banner2.cleanpng.com/20180615/txk/kisspng-barbecue-sauce-jalapeo-tabasco-pepper-hot-sauce-sauce-bottles-5b2397c81a8f24.7509530515290592721088.jpg','https://www.seekpng.com/png/detail/396-3965148_heinz-tomato-ketchup-1-kg-57-on-heinz.png','https://cdn1.yopongoelhielo.com/4113/moutarde-jaune-heinz.jpg','https://simpplier.com/wp-content/uploads/104508_spanish_extra_virgin_olive_oil_glass_marasca_bottle_1lt_monteagle_brand_simpplier.png'],
            'Pasta & rice' => ['https://s3.eu-west-2.amazonaws.com/devo.core.images/products/c901b87d-46ae-4a67-a3bd-3fc0ac1f938d_8006222300036.png','https://www.pngitem.com/pimgs/m/148-1483907_zinetti-s-frozen-vegetable-lasagna-lasagne-pack-hd.png','https://www.seekpng.com/png/detail/900-9003573_aruba-brown-rice-basmati.png','https://www.pngitem.com/pimgs/m/52-524910_indomie-instant-noodles-hd-png-download.png'],
            'Snacks' => ['https://www.pngitem.com/pimgs/m/53-539456_dairy-milk-bubbly-price-hd-png-download.png','https://www.pngkey.com/png/detail/110-1101283_chips-bag-png-clipart-freeuse-library-lays-salt.png','https://www.clipartkey.com/mpngs/m/10-109115_oreo-clipart-package-oreo-14-3-oz.png','https://www.cdiscount.com/pdt2/3/7/9/3/550x550/wer2009260811379/rw/werthers-original-caramel-popcorn-classic-140g-pa.jpg'],
            'Fruits'=> ['https://img.favpng.com/10/4/6/fruit-packaging-and-labeling-apple-packungsdesign-product-png-favpng-vXJ6mJiUmg0kHibwpzP9Bti0F.jpg','https://s.wsj.net/public/resources/images/BA-BE539_smcapS_KS_20140314010117.jpg','https://img.favpng.com/3/17/17/strawberry-shortcake-bubble-tape-well-pict-png-favpng-FDnZgEy8gQZbJ7cFPtaTzCe50.jpg','https://www.shoprite.co.za/medias/10620839EA-20190726-Media-checkers515Wx515H?context=bWFzdGVyfGltYWdlc3wyODcwNDd8aW1hZ2UvcG5nfGltYWdlcy9oMGQvaDZiLzg4NjAyMjc4OTUzMjYucG5nfGU4ZWEyM2JhNWU2YjVlNDkyMGQwNTVmM2IxMDk4NzNjMjNlMjg4MDc2YzM0NGIzMGJkZGM5OWFlYmNlNzgzYjA'],
            'Vegetables' => ['https://cdn.happyfresh.com/t/s_large,c_fit/spree/images/attachments/9c38c406027213959c65184f2c5ca3286a18ef84-original.jpg','https://groceries.morrisons.com/productImages/289/289912011_0_640x640.jpg?identifier=9162406e973a51d9380efe7a0ae6c440','https://1372d34c156f12457517-c8b49206fc42ea00d5fd50b8ec61d670.ssl.cf2.rackcdn.com/0057836168260_A1L1_ItemMaster_default_large.jpeg','https://digitalcontent.api.tesco.com/v2/media/ghs/3098b228-8a86-4a2f-95f5-9b5878664dfb/snapshotimagehandler_807880848.jpeg?h=540&w=540','https://images.freshop.com/00033383666020/feb239b6f518ce14dee575d3548c5b7e_large.png'],
            'Canned' => ['https://www.nicepng.com/png/detail/233-2332992_during-the-event-mega-tuna-also-introduced-their.png','https://www.pngitem.com/pimgs/m/18-186751_can-of-baked-beans-hd-png-download.png','https://m.media-amazon.com/images/I/81da1UHLb3L._SX679_.jpg','https://www.pngitem.com/pimgs/m/111-1110387_canned-sweet-corn-png-download-csemege-kukorica-transparent.png','https://www.agidra.com/images/vignettes/167033_T1.jpg'],
            'Meat' => ['https://w7.pngwing.com/pngs/481/63/png-transparent-packaging-and-labeling-meat-packing-industry-food-meat-beef-label-roast-beef.png','https://www.seekpng.com/png/detail/46-460731_family-pack-drumsticks-pack-of-chicken-legs.png','https://cdn.shopify.com/s/files/1/0307/2033/products/HA174PRMFreshSalmonPortionsSkinOnMAP280gPackVisual_grande.png?v=1660275974','https://www.becampbell.com.au/wp-content/uploads/cache/2018/03/www.becampbell.com.au-bruemar-porkmignonswithcoriandercuminlemoncore-clear-web-1500x1000.jpg','https://sob-prd-cdn-products.azureedge.net/media/image/product/fr/large/0062784351741.jpg','https://cdn.shopify.com/s/files/1/0403/2203/9970/products/SparLambchops4_s02.png?v=1628846933','https://www.pngitem.com/pimgs/m/69-695444_hickory-smoked-white-turkey-fast-food-hd-png.png','https://d1ssu070pg2v9i.cloudfront.net/pex/simonhowie/2021/09/20090719/Unsmoked-bacon-Medallions-2021-MAIN.png']
        ];
        foreach($categories as $categ){    
        $id = Categorie::where('name',$categ)->first()->id;
        foreach(array_combine($names[$categ],$pictures[$categ]) as $name =>$pic){

        Product::factory()
        ->create(['name' => $name,
                  'picture' =>$pic,
        ])
        ->categories()->attach($id);
    }
    }}
}
