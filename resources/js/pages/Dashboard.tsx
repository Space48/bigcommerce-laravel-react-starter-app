import React, {useMemo, useState} from 'react';
import {ContentLoading, PageBody, PageHeader} from '../components';
import {Panel} from '@bigcommerce/big-design';
import {useParams} from 'react-router-dom';
import {useProducts} from '../hooks';
import ProductsTable from '../components/ProductsTable';
import {ProductPagination} from '../types';

const Dashboard = () => {
  const [page, setPage] = useState<number>(1);
  const [perPage, setPerPage] = useState<number>(25);
  const {store_hash} = useParams();
  const [productsData, productMeta, productError, isProductsLoading] = useProducts(store_hash, {
    page: page,
    limit: perPage,
    include: 'images'
  })

  const pagination: ProductPagination = useMemo(() => {
    return {
      currentPage: productMeta?.pagination?.current_page ?? 1,
      itemsPerPage: productMeta?.pagination?.per_page ?? 1,
      itemsPerPageOptions: [50, 100, 200],
      totalItems: productMeta?.pagination?.total ?? 1,
      onPageChange: (page) => setPage(page),
      onItemsPerPageChange: (amount) => setPerPage(amount)
    }
  }, [productMeta, perPage, page]);

  return (
    <>
      <PageHeader title="Example Product List" />
      <PageBody>
        <ContentLoading
          loading={isProductsLoading}
          error={productError}
        >
          <Panel>
            <ProductsTable
              products={productsData}
              pagination={pagination}
            />
          </Panel>
        </ContentLoading>
      </PageBody>
    </>
  );
}

export default Dashboard;
