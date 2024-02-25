<div>
      <x-forms.input-grid col1="12" col2="6" label="Product Name" name="product_name"
          type="text" id="product_name" value="{{ $product->product_name ?? '' }}"
          placeholder="Insert product name..."></x-forms.input-grid>
      <x-forms.textarea col1="2" col2="6" label="Product Description" value="{{ $product->product_description ?? '' }}"
          name="product_description" id="product_description"/>

      <div class="row row-cols-2">
          <x-forms.input-grid col1="12" col2="12" label="Product Capitral Price" name="product_price_capital"
              type="number" value="{{ $product->product_price_capital ?? '' }}"
              placeholder="Insert product capital price"></x-forms.input-grid>
          <x-forms.input-grid col1="12" col2="12" label="Product Selling Price" name="product_price_sell"
              type="number" value="{{ $product->product_price_sell ?? '' }}"
              placeholder="Insert product selling price"></x-forms.input-grid>
      </div>
  </div>