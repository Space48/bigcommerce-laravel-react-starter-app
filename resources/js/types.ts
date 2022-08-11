import {ButtonProps} from "@bigcommerce/big-design/dist/components/Button/Button";

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