import {ButtonProps} from '@bigcommerce/big-design/dist/components/Button/Button';
import {TablePaginationProps} from '@bigcommerce/big-design/dist/components/Table/types';

export interface ContextType {
    appId: string;
    appName: string;
    noticeableProjectId: string;
    noticeableLabelId: string;
    noticeableToken: string;
    supportDocsUrl: string;
    supportDocsFAQUrl: string;
    supportEmail: string;
    appDescription: string;
}

export interface PageHeaderActions extends ButtonProps {
    text: string;
}

export interface Location {
    state: {
        referrer?: string;
    }
}

export interface ProductImage {
    url_thumbnail: string;
}

export interface Product {
    sku: string;
    name: string;
    price: number;
    inventory_level: number;
    custom_url: {
        url: string;
    }
    images: ProductImage[]
}

export interface ProductPagination extends TablePaginationProps {

}