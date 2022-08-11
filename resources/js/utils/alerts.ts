import {createAlertsManager} from "@bigcommerce/big-design";

export const alertsManager: any = createAlertsManager();

export const addAlert = (type, message, header: string | null = null,  autoDismiss = true) => {
  alertsManager.add({
    type: type,
    ...(header ? {header: header} : {}),
    autoDismiss,
    messages: [{text: message}]
  });
}

export const notifyError = (message, header: string | null = null) => {
  addAlert('error', message, header);
}

export const notifySuccess = (message, header: string | null = null) => {
  addAlert('success', message, header);
}

export const notifyInfo = (message, header: string | null = null) => {
  addAlert('info', message, header);
}
