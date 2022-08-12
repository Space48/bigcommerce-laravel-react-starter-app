import React from 'react';
import {Table} from '@bigcommerce/big-design';
import styled from 'styled-components';
import {Product, ProductPagination} from '../types';

interface Props {
  products: Product[],
  pagination: ProductPagination,
}

const Image = styled.img`
  max-width: 75px;
  margin: 0px auto;
`

const ProductsTable = ({products, pagination}: Props) => {
  console.log('PRODUCTS', products);
  return products ? (
    <Table
      itemName="Products"
      keyField="sku"
      pagination={pagination}
      columns={[
        {
          header: 'Image',
          hash: 'image',
          render: ({ name, images }) =>  <Image src={images[0]?.url_thumbnail ?? '/img/product-default.gif'} alt={name} />,
        },
        {
          header: 'Sku',
          hash: 'sku',
          render: ({ sku }) => sku,
        },
        { header: 'Name', hash: 'name', render: ({ name }) => name },
        { header: 'Price', hash: 'price', render: ({ price }) => `£${price.toFixed(2)}` },
      ]}
      items={products}
    />
  ) : null;
}

export default ProductsTable;
