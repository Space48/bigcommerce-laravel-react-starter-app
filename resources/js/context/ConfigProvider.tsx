// @ts-nocheck
import React from 'react';
import ConfigContext from './ConfigContext';

interface Props {
  children: React.ReactNode
}

const ConfigProvider = ({children}: Props) => {
  // .content here flags in TS
  const appId = document.head.querySelector('meta[name="app-id"]').content;
  const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

  return (
    <ConfigContext.Provider value={{
      appId,
      appName: 'BigCommerce App',
      appDescription: 'The core of Space 48 Apps',
      csrfToken,
    }}>
      {children}
    </ConfigContext.Provider>
  );
};

export default ConfigProvider;
