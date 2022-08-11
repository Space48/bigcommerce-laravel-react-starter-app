import { SelectOption } from "@bigcommerce/big-design/dist/components/Select/types";
import React, {ComponentProps, ForwardedRef} from "react";
import {ButtonProps} from "@bigcommerce/big-design/dist/components/Button/Button";
import {UseComboboxGetItemPropsOptions} from "downshift";
import {DefaultTheme} from "styled-components";

export interface Customer {
    name?: string | undefined;
    taxIdType?: string | undefined;
    taxId?: string | undefined;
}

export interface TaxIdType extends SelectOption<string> {
    value: string;
    content: string;
    example?: string;
}

export interface HookParams {
    store_hash: string;
}

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

export interface CookieOptions {
    path: string;
    sameSite: boolean | "none" | "lax" | "strict";
    secure: boolean;
}


export interface GetItemProps {
    onClick: any;
    index: number;
    item: any;
    ref: any;
}

export interface StyleableListProps extends ComponentProps<any> {
    theme?: DefaultTheme;
    autoWidth?: boolean;
    forwardedRef?: ForwardedRef<any>;
    getItemProps: (options: UseComboboxGetItemPropsOptions<any>) => any;
    getMenuProps?: any;
    highlightedIndex?: number;
    isOpen?: boolean;
    items?: [];
    maxHeight?: number;
    update: any;
    onItemChosen: () => void;
    ItemComponent: React.FC;
}

export interface StyleableListItemProps extends ComponentProps<any> {
    theme?: DefaultTheme;
    autoWidth?: boolean;
    forwardedRef?: ForwardedRef<any>;
    ItemComponent: React.FC;
    index?: number;
    isHighlighted?: boolean;
    item?: any;
    getItemProps: (item: UseComboboxGetItemPropsOptions<any>) => any;
    onChosen: (item: string) => void;
    isSelected?: boolean;
    isAction?: boolean;
    actionType?: string;
    disabled?: boolean;
    children?: React.ReactNode;
}

export interface StyleableButtonProps extends ComponentProps<any> {
    isLoading?: boolean;
    forwardedRef?: ForwardedRef<any>;
    children?: React.ReactNode;
    style?: Record<string, string|number>;
}

export interface TimelineItem {
    id: string;
    title: string;
    excerpt: string;
    permalink: string;
    publicationTime: string;
    description: string;
    url: string;

}

export interface TimelineAction {
    onClick: () => void | Window | null;
    label: string;
}

export interface InvoiceColumnRenderProps {
    number: number;
    created: number;
    total: any;
    currency: string;
    status: string;
    hosted_invoice_url: string;
}

export interface PageHeaderActions extends ButtonProps {
    text: string;
}

export interface Location {
    state: {
        referrer?: string;
    }
}

export interface Categories {
    id: string;
    name: string;
    children: [];
}