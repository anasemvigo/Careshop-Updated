<?php
/**
 * @author EmvigoTech
 * @copyright 2025 EmvigoTech
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */
namespace EmvigoTech\ShippingLabel\Model\Order\Pdf;

/**
 * Sales Order Shipment PDF model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Shipment extends \Magento\Sales\Model\Order\Pdf\Shipment
{
    /**
     * Return PDF document
     *
     * @param \Magento\Sales\Model\Order\Shipment[] $shipments
     * @return \Zend_Pdf
     */
    public function getPdf($shipments = [])
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('shipment');

        $pdf = new \Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new \Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($shipments as $shipment) {
            $storeId = $shipment->getStoreId();
            if ($shipment->getStoreId()) {
                $this->_localeResolver->emulate($shipment->getStoreId());
                $this->_storeManager->setCurrentStore($shipment->getStoreId());
            }

            $page = $this->newPage();
            $order = $shipment->getOrder();
            $this->drawDeliveryReturnSlipHeader($page, $storeId);
            /* Add image */
            $this->insertLogo($page, $shipment->getStore());
            /* Add address */
            $this->insertAddress($page, $shipment->getStore());
            $this->insertShippingAddressBlock($page, $order);
            $this->insertOrderInfo($page, $order, $this->_scopeConfig->isSetFlag(
                self::XML_PATH_SALES_PDF_SHIPMENT_PUT_ORDER_ID,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $order->getStoreId()
            ));
            /* Add table */
            $this->_drawHeader($page, $storeId);
            /* Add body */
            foreach ($shipment->getAllItems() as $item) {
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }
            if ($shipment->getStoreId()) {
                $this->_localeResolver->revert();
            }

            // --- RETURN SLIP PAGE (as per image) ---
            $returnPage = $pdf->pages[] = new \Zend_Pdf_Page(\Zend_Pdf_Page::SIZE_A4);

            // Draw "RETURN SLIP" header centered at the top
            $this->_setFontBold($returnPage, 16);
            switch ($storeId) {
                case 1:
                    $headerText = __('RETURN SLIP');
                    break;
                case 4:
                    $headerText = __('RÜCKSENDESCHEIN');
                    break;
                case 3:
                    $headerText = __('BON DE RETOUR');
                    break;
                case 5:
                    $headerText = __('BOLLA DI RESO');
                    break;
                default:
                    $headerText = __('RETURN SLIP');
                    break;
            }
            $this->insertLogo($returnPage, $shipment->getStore());
            $headerWidth = $this->widthForStringUsingFontSize($headerText, $returnPage->getFont(), 16);
            $headerX = (595.28 - $headerWidth) / 2; // A4 width is 595.28pt
            $returnPage->drawText($headerText, $headerX, 800, 'UTF-8');

            // Draw Customer Address (left) and Return Address (right) in two columns
            $this->_setFontBold($returnPage, 11);
            switch ($storeId) {
                case 1:
                    $customerAddressLabel = __('Customer Address:');
                    break;
                case 4:
                    $customerAddressLabel = __('Kundenadresse:');
                    break;
                case 3:
                    $customerAddressLabel = __('Adresse du client :');
                    break;
                case 5:
                    $customerAddressLabel = __('Indirizzo cliente:');
                    break;
                default:
                    $customerAddressLabel = __('Customer Address:');
                    break;
            }
            $returnPage->drawText($customerAddressLabel, 30, 710, 'UTF-8');
            $this->_setFontBold($returnPage, 11);
            switch ($storeId) {
                case 1:
                    $returnAddressLabel = __('Return Address:');
                    break;
                case 4:
                    $returnAddressLabel = __('Rücksendeadresse:');
                    break;
                case 3:
                    $returnAddressLabel = __('Adresse de retour :');
                    break;
                case 5:
                    $returnAddressLabel = __('Indirizzo di reso:');
                    break;
                default:
                    $returnAddressLabel = __('Return Address:');
                    break;
            }
            $returnPage->drawText($returnAddressLabel, 400, 710, 'UTF-8');

            // Prepare address lines
            $this->_setFontRegular($returnPage, 10);

            // Customer Address
            $customerAddress = $this->_formatAddress(
                $this->addressRenderer->format($order->getShippingAddress(), 'pdf')
            );
            $yCustomer = 690;
            foreach ($customerAddress as $line) {
                $returnPage->drawText($line, 30, $yCustomer, 'UTF-8');
                $yCustomer -= 12;
            }

            // Return Address (store address)
            $returnAddress = $this->_scopeConfig->getValue(
                'shipping_label/general/address',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $shipment->getStore()
            );
            $returnAddressLines = explode("\n", preg_replace('/<br[^>]*>/i', "\n", $returnAddress));
            $yReturn = 690;
            foreach ($returnAddressLines as $line) {
                $returnPage->drawText(trim(strip_tags($line)), 400, $yReturn, 'UTF-8');
                $yReturn -= 12;
            }

            // Draw Order ID and Date
            $this->_setFontBold($returnPage, 10);
            switch ($storeId) {
                case 1:
                    $returnPage->drawText(__('Order ID:'), 30, 600, 'UTF-8');
                    break;
                case 4:
                    $returnPage->drawText(__('Bestellnummer:'), 30, 600, 'UTF-8');
                    break;
                case 3:
                    $returnPage->drawText(__('Numéro de commande:'), 30, 600, 'UTF-8');
                    break;
                case 5:
                    $returnPage->drawText(__('ID ordine:'), 30, 600, 'UTF-8');
                    break;
                default:
                    $returnPage->drawText(__('Order ID:'), 30, 600, 'UTF-8');
                    break;
            }
            $this->_setFontRegular($returnPage, 10);
            switch ($storeId) {
                case 1:
                    $returnPage->drawText($order->getRealOrderId(), 80, 600, 'UTF-8');
                    break;
                case 4:
                    $returnPage->drawText($order->getRealOrderId(), 125, 600, 'UTF-8');
                    break;
                case 3:
                    $returnPage->drawText($order->getRealOrderId(), 135, 600, 'UTF-8');
                    break;
                case 5:
                    $returnPage->drawText($order->getRealOrderId(), 135, 600, 'UTF-8');
                    break;
                default:
                    $returnPage->drawText($order->getRealOrderId(), 80, 600, 'UTF-8');
                    break;
            }


            $this->_setFontBold($returnPage, 10);
            switch ($storeId) {
                case 1:
                    $returnPage->drawText(__('Order Date:'), 30, 590, 'UTF-8');
                    break;
                case 4:
                    $returnPage->drawText(__('Bestelldatum:'), 30, 590, 'UTF-8');
                    break;
                case 3:
                    $returnPage->drawText(__('Date de commande :'), 30, 590, 'UTF-8');
                    break;
                case 5:
                    $returnPage->drawText(__('Data ordine:'), 30, 590, 'UTF-8');
                    break;
                default:
                    $returnPage->drawText(__('Order Date:'), 30, 590, 'UTF-8');
                    break;
            }
            $this->_setFontRegular($returnPage, 10);
            switch ($storeId) {
                case 1:
                    $returnPage->drawText($order->getCreatedAt(), 90, 590, 'UTF-8');
                    break;
                case 4:
                    $returnPage->drawText($order->getCreatedAt(), 125, 590, 'UTF-8');
                    break;
                case 3:
                    $returnPage->drawText($order->getCreatedAt(), 135, 590, 'UTF-8');
                    break;
                case 5:
                    $returnPage->drawText($order->getCreatedAt(), 135, 590, 'UTF-8');
                    break;
                default:
            }
            $this->_setFontBold($returnPage, 10);

            // Table starting position
            $tableTop = 550;
            $lineHeight = 15;
            $currentY = $tableTop;

            // Column headers
            switch ($storeId) {
                case 1:
                    $headers = [
                        ['text' => 'Return', 'x' => 30],
                        ['text' => 'SKU / Product', 'x' => 80],
                        ['text' => 'QTY', 'x' => 250],
                        ['text' => 'Reason for Return', 'x' => 300],
                        ['text' => 'Description of Reason', 'x' => 460],
                    ];
                    break;
                    case 4: // German - de_DE
                        $headers = [
                            ['text' => 'Rückgabe', 'x' => 30],
                            ['text' => 'Artikelnummer / Produkt', 'x' => 80],
                            ['text' => 'MENGE', 'x' => 250], // Placeholder - not in CSV
                            ['text' => 'Rücksendegrund', 'x' => 300],
                            ['text' => 'Beschreibung des Grundes', 'x' => 460],
                        ];
                        break;

                    case 3: // French - fr_FR
                        $headers = [
                            ['text' => 'Retour', 'x' => 30],
                            ['text' => 'SKU / Produit', 'x' => 80],
                            ['text' => 'Qté', 'x' => 250], // Placeholder - not in CSV
                            ['text' => 'Motif du retour', 'x' => 300],
                            ['text' => 'Description du motif', 'x' => 460],
                        ];
                        break;

                    case 5: // Italian - it_IT
                        $headers = [
                            ['text' => 'Reso', 'x' => 30],
                            ['text' => 'SKU / Prodotto', 'x' => 80],
                            ['text' => 'Qtà', 'x' => 250], // Placeholder - not in CSV
                            ['text' => 'Motivo del reso', 'x' => 300],
                            ['text' => 'Descrizione del motivo', 'x' => 460],
                        ];
                        break;
                default:
                    $headers = [
                        ['text' => 'Return', 'x' => 30],
                        ['text' => 'SKU / Product', 'x' => 80],
                        ['text' => 'QTY', 'x' => 250],
                        ['text' => 'Reason for Return', 'x' => 300],
                        ['text' => 'Description of Reason', 'x' => 460],
                    ];
                    break;
            }

            foreach ($headers as $header) {
                $returnPage->drawText(__($header['text']), $header['x'], $currentY, 'UTF-8');
            }

            $currentY -= $lineHeight;
            $this->_setFontRegular($returnPage, 9);

            // Draw product rows
            foreach ($shipment->getAllItems() as $item) {
                $orderItem = $item->getOrderItem();
                if ($orderItem->getParentItem()) {
                    continue;
                }

                $sku = $orderItem->getSku();
                // Limit SKU to 20 characters
                if (strlen($sku) > 40) {
                    $sku = substr($sku, 0, 30) . '...';
                }

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $productRepository = $objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
                $productId = $orderItem->getProductId();
                $product = $productRepository->getById($productId, false, $storeId);
                $name = $product->getName();;
                $qty = '---------';

                // Return checkbox (default unchecked)
                $returnPage->drawText('[   ]', 35, $currentY, 'UTF-8');

                // SKU and Product Name (wrapped if long)
                $returnPage->drawText($sku, 80, $currentY, 'UTF-8');
                $currentY -= $lineHeight;
                // Split name into two lines if it's too long
                $nameLength = strlen($name);
                $maxLength = 20; // Maximum characters per line

                if ($nameLength > $maxLength) {
                    // Find a good breaking point (space near the middle)
                    $midPoint = (int)($nameLength / 2);
                    $breakPoint = strpos($name, ' ', $midPoint);

                    if ($breakPoint === false) {
                        // If no space found after midpoint, look before midpoint
                        $breakPoint = strrpos(substr($name, 0, $midPoint), ' ');
                    }

                    if ($breakPoint !== false) {
                        $line1 = substr($name, 0, $breakPoint);
                        $line2 = substr($name, $breakPoint + 1);

                        $returnPage->drawText($line1, 80, $currentY, 'UTF-8');
                        $currentY -= $lineHeight;
                        $returnPage->drawText($line2, 80, $currentY, 'UTF-8');
                    } else {
                        // If no space found, just draw the name as is
                        $returnPage->drawText($name, 80, $currentY, 'UTF-8');
                    }
                } else {
                    // Name is short enough for one line
                    $returnPage->drawText($name, 80, $currentY, 'UTF-8');
                }

                // Quantity
                $returnPage->drawText((string)$qty, 250, $currentY + $lineHeight, 'UTF-8');

                // Return Reasons checklist
                switch ($storeId) {
                    case 1:
                        $reasons = '[ ] Too Small  [ ] Too Big';
                        $reasons1 = '[ ] Wrong Item [ ] Don\'t Like';
                        $reasons2 = "[ ] Defective  [ ] Other";
                        break;
                    case 4:
                        $reasons = '[ ]Zu klein [ ]Zu groß';
                        $reasons1 = '[ ]Falscher Artikel [ ]Gefällt mir nicht';
                        $reasons2 = '[ ]Defekt [ ]Sonstiges';
                        break;
                    case 3:
                        $reasons = '[ ]Trop petit [ ]Trop grand';
                        $reasons1 = "[ ]Mauvais article [ ]N'aime pas";
                        $reasons2 = "[ ]Défectueux [ ]Autre";
                        break;
                    case 5:
                        $reasons = '[ ]Troppo piccolo [ ]Troppo grande';
                        $reasons1 = '[ ]Articolo errato [ ]Non piace';
                        $reasons2 = '[ ]Difettoso [ ]Altro';
                        break;
                    default:
                        $reasons = '[ ] Too Small  [ ] Too Big';
                        $reasons1 = '[ ] Wrong Item [ ] Don\'t Like';
                        $reasons2 = "[ ] Defective  [ ] Other";
                        break;
                }
                $returnPage->drawText($reasons, 300, $currentY + $lineHeight, 'UTF-8');
                $returnPage->drawText($reasons2, 300, $currentY - $lineHeight, 'UTF-8');
                $returnPage->drawText($reasons1, 300, $currentY, 'UTF-8');

                // Description dotted line
                $dots = str_repeat('.', 55);
                $returnPage->drawText($dots, 460, $currentY + $lineHeight, 'UTF-8');
                $returnPage->drawText($dots, 460, $currentY, 'UTF-8');

                // Move to next row (add extra space after each item)
                $currentY -= ($lineHeight * 2);
            }

        // Draw "Additional Comments:" label
            $commentsLabelY = $currentY - 10;
            switch ($storeId) {
                case 1:
                    $returnPage->drawText('Additional Comments:', 80, $commentsLabelY, 'UTF-8');
                    break;
                case 4:
                    $returnPage->drawText('Zusätzliche Kommentare:', 80, $commentsLabelY, 'UTF-8');
                    break;
                case 3:
                    $returnPage->drawText('Commentaires supplémentaires:', 80, $commentsLabelY, 'UTF-8');
                    break;
                case 5:
                    $returnPage->drawText('Commenti aggiuntivi:', 80, $commentsLabelY, 'UTF-8');
                    break;
                default:
                    $returnPage->drawText('Additional Comments:', 80, $commentsLabelY, 'UTF-8');
                    break;
            }

        // Draw 4 horizontal lines for comments
            $lineStartX = 80;
            $lineEndX = 540;
            $lineSpacing = 18;
            $commentsLineY = $commentsLabelY - 35;
            $returnPage->setLineWidth(1);

            for ($i = 0; $i < 4; $i++) {
                $y = $commentsLineY - ($i * $lineSpacing);
                $returnPage->drawLine($lineStartX, $y, $lineEndX, $y);
            }

        }

        $this->_afterGetPdf();
        return $pdf;
    }

    protected function drawDeliveryReturnSlipHeader(\Zend_Pdf_Page $page, $storeId)
    {
        $this->_setFontBold($page, 14);
        switch ($storeId) {
            case 1:
                $page->drawText(__('DELIVERY / RETURN SLIP'), 200, $this->y, 'UTF-8');
                break;
            case 4:
                $page->drawText(__('LIEFERSCHEIN / RÜCKSENDESCHEIN'), 200, $this->y, 'UTF-8');
                break;
            case 3:
                $page->drawText(__('BON DE LIVRAISON / RETOUR'), 200, $this->y, 'UTF-8');
                break;
            case 5:
                $page->drawText(__('BOLLA DI CONSEGNA / RESO'), 200, $this->y, 'UTF-8');
                break;
            default:
                $page->drawText(__('DELIVERY / RETURN SLIP'), 200, $this->y, 'UTF-8');
                break;
        }
        $this->y -= 30;
    }

    /**
     * Draws the "Ship to:" block at the right, with the shipping address formatted as in the provided image.
     */
    protected function insertShippingAddressBlock(\Zend_Pdf_Page $page, $order)
    {
        // Block position
        $blockTop = 710;
        $blockLeft = 400;
        $storeId = $order->getStoreId();
        // Draw "Ship to:" in bold
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->_setFontBold($page, 12);
        switch ($storeId) {
            case 1:
                $page->drawText(__('Ship to:'), $blockLeft, $blockTop, 'UTF-8');
                break;
            case 4:
                $page->drawText(__('Versand an:'), $blockLeft, $blockTop, 'UTF-8');
                break;
            case 3:
                $page->drawText(__('Livrer à :'), $blockLeft, $blockTop, 'UTF-8');
                break;
            case 5:
                $page->drawText(__('Spedire a:'), $blockLeft, $blockTop, 'UTF-8');
                break;
            default:
                $page->drawText(__('Ship to:'), $blockLeft, $blockTop, 'UTF-8');
                break;
        }

        // Prepare shipping address lines
        $shippingAddress = $this->_formatAddress(
            $this->addressRenderer->format($order->getShippingAddress(), 'pdf')
        );
        if ($shippingAddress) {
            // Draw address lines under "Ship to:"
            $this->_setFontRegular($page, 11);
            $lineHeight = 14;
            $currentY = $blockTop - 18;
            foreach ($shippingAddress as $line) {
                $page->drawText($line, $blockLeft, $currentY, 'UTF-8');
                $currentY -= $lineHeight;
            }
        }
    }

    protected function insertAddress(&$page, $store = null)
    {
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $font = $this->_setFontRegular($page, 10);
        $page->setLineWidth(0);

        $top = $this->y ? $this->y : 815;
        $top -= 10;

        // Custom address logic
        $rawAddress = $this->_scopeConfig->getValue(
            'shipping_label/general/address',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );

        // Example: Add store name above address
        $storeName = $this->_scopeConfig->getValue(
            'general/store_information/name',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );

        if ($storeName) {
            $page->drawText($storeName, 35, $top, 'UTF-8');
            $top -= 12;
        }

        $values = explode("\n", preg_replace('/<br[^>]*>/i', "\n", $rawAddress));

        foreach ($values as $value) {
            if ($value !== '') {
                foreach ($this->string->split($value, 45, true, true) as $_value) {
                    $page->drawText(trim(strip_tags($_value)), 30, $top, 'UTF-8');
                    $top -= 10;
                }
            }
        }

        $this->y = $top;
    }

    protected function insertLogo(&$page, $store = null)
    {
        $this->y = $this->y ? $this->y : 815;

        $image = $this->_scopeConfig->getValue(
            'sales/identity/logo',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );

        if ($image) {
            $imagePath = '/sales/store/logo/' . $image;

            if ($this->_mediaDirectory->isFile($imagePath)) {
                $image = \Zend_Pdf_Image::imageWithPath($this->_mediaDirectory->getAbsolutePath($imagePath));

                // ✅ Customize this line to change vertical position of the logo
                $top = 750;

                $widthLimit = 120;
                $heightLimit = 120;

                $width = $image->getPixelWidth();
                $height = $image->getPixelHeight();
                $ratio = $width / $height;

                if ($ratio > 1 && $width > $widthLimit) {
                    $width = $widthLimit;
                    $height = $width / $ratio;
                } elseif ($ratio < 1 && $height > $heightLimit) {
                    $height = $heightLimit;
                    $width = $height * $ratio;
                } elseif ($ratio == 1 && $height > $heightLimit) {
                    $height = $heightLimit;
                    $width = $widthLimit;
                }

                $y1 = $top - $height;
                $y2 = $top;
                $x1 = 29;
                $x2 = $x1 + $width;

                $page->drawImage($image, $x1, $y1, $x2, $y2);

                $this->y = $y1 - 10; // update y for subsequent elements
            }
        }
    }

    protected function insertOrderInfo(\Zend_Pdf_Page $page, $order, $putOrderId)
    {
        if ($putOrderId) {
            $top = 600;
            $storeId = $order->getStoreId();
            // Draw "Order #" label in bold
            $this->_setFontBold($page, 10);
            switch ($storeId) {
                case 1:
                    $page->drawText(__('Order ID:      '), 30, $top -= 30, 'UTF-8');
                    break;
                case 4:
                    $page->drawText(__('Bestellnummer:      '), 30, $top -= 30, 'UTF-8');
                    break;
                case 3:
                    $page->drawText(__('Numéro de commande :     '), 30, $top -= 30, 'UTF-8');
                    break;
                case 5:
                    $page->drawText(__('ID ordine:      '), 30, $top -= 30, 'UTF-8');
                   break;
                default:
                    $page->drawText(__('Order ID:      '), 30, $top -= 30, 'UTF-8');
                    break;
            }

            // Draw order number in regular font
            $this->_setFontRegular($page, 10);
            $orderNumberX = 35 + $this->widthForStringUsingFontSize(__('Order #    '), $page->getFont(), 10);

            switch ($storeId) { 
                case 1:
                    $page->drawText($order->getRealOrderId(), $orderNumberX, $top, 'UTF-8');
                    break;
                case 4:
                    $page->drawText($order->getRealOrderId(), 125, $top, 'UTF-8');
                    break;
                case 3:
                    $page->drawText($order->getRealOrderId(), 135, $top, 'UTF-8');
                    break;
                case 5:
                    $page->drawText($order->getRealOrderId(), 135, $top, 'UTF-8');
                    break;
                default:
                    $page->drawText($order->getRealOrderId(), 80, $top, 'UTF-8');
                    break;
            }
            // Draw "Order Date:" label in bold
            $this->_setFontBold($page, 10);
            switch ($storeId) {
                case 1:
                    $page->drawText(__('Order Date:    '), 30, $top -= 15, 'UTF-8');
                    break;
                case 4:
                    $page->drawText(__('Bestelldatum:    '), 30, $top -= 15, 'UTF-8');
                    break;
                case 3:
                    $page->drawText(__('Date de commande:      '), 30, $top -= 15, 'UTF-8');
                    break;
                case 5:
                    $page->drawText(__('Data ordine:      '), 30, $top -= 15, 'UTF-8');
                    break;
                default:
                    $page->drawText(__('Order Date:    '), 30, $top -= 15, 'UTF-8');
                    break;
            }

            // Draw order date in regular font
            $this->_setFontRegular($page, 10);
            $orderDateX = 35 + $this->widthForStringUsingFontSize(__('Order Date:    '), $page->getFont(), 10);
            switch ($storeId) {
                case 1:
                    $page->drawText($order->getCreatedAt(), $orderDateX, $top, 'UTF-8');
                    break;
                case 4:
                    $page->drawText($order->getCreatedAt(), 135, $top, 'UTF-8');
                    break;
                case 3:
                    $page->drawText($order->getCreatedAt(), 135, $top, 'UTF-8');
                    break;
                case 5:
                    $page->drawText($order->getCreatedAt(), 135, $top, 'UTF-8');
                    break;
                default:
                    $page->drawText($order->getCreatedAt(), 80, $top, 'UTF-8');
                    break;

            }

        }
    }

    /**
     * Calculate the width of a string for a given font and size.
     *
     * @param string $string
     * @param \Zend_Pdf_Resource_Font $font
     * @param int $fontSize
     * @return float
     */
    public function widthForStringUsingFontSize($string, $font, $fontSize)
    {
        $drawingString = iconv('UTF-8', 'UTF-16BE//IGNORE', $string);
        $characters = [];
        for ($i = 0; $i < strlen($drawingString); $i += 2) {
            $characters[] = (ord($drawingString[$i]) << 8) | ord($drawingString[$i + 1]);
        }
        $glyphs = $font->glyphNumbersForCharacters($characters);
        $widths = $font->widthsForGlyphs($glyphs);
        $stringWidth = (array_sum($widths) / $font->getUnitsPerEm()) * $fontSize;
        return $stringWidth;
    }

    protected function _drawHeader(\Zend_Pdf_Page $page, $storeId = null)
    {
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $this->y -= 160;
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));

        //columns headers
        switch ($storeId) {
            case 1:
                $lines[0][] = ['text' => __('Products'), 'feed' => 100, 'font' => 'bold', 'align' => 'center'];
                break;
            case 4:
                $lines[0][] = ['text' => __('Produkte'), 'feed' => 100, 'font' => 'bold', 'align' => 'center'];
                break;
            case 3:
                $lines[0][] = ['text' => __('Produits'), 'feed' => 100, 'font' => 'bold', 'align' => 'center'];
                break;
            case 5:
                $lines[0][] = ['text' => __('Prodotti'), 'feed' => 100, 'font' => 'bold', 'align' => 'center'];
                break;
            default:
                $lines[0][] = ['text' => __('Products'), 'feed' => 100, 'font' => 'bold', 'align' => 'center'];
                break;
        }

        switch ($storeId) {
            case 1:
                $lines[0][] = ['text' => __('Qty'), 'feed' => 30, 'font' => 'bold', 'align' => 'center'];
                break;
            case 4:
                $lines[0][] = ['text' => __('Menge'), 'feed' => 30, 'font' => 'bold', 'align' => 'center'];
                break;
            case 3:
                $lines[0][] = ['text' => __('Qté'), 'feed' => 30, 'font' => 'bold', 'align' => 'center'];
                break;
            case 5:
                $lines[0][] = ['text' => __('Qtà'), 'feed' => 30, 'font' => 'bold', 'align' => 'center'];
                break;
            default:
                $lines[0][] = ['text' => __('Qty'), 'feed' => 30, 'font' => 'bold', 'align' => 'center'];
                break;
        }

        $lines[0][] = ['text' => __('SKU'), 'feed' => 565, 'align' => 'right', 'font' => 'bold'];

        $lineBlock = ['lines' => $lines, 'height' => 10];

        $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }
}